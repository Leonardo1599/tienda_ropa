<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Carrito;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OrdenController extends Controller
{
    public function index()
    {
        $ordenes = Orden::where('user_id', Auth::id())->get();
        return view('dashboard.ordenes', compact('ordenes'));
    }

    public function store(Request $request)
    {
        $userId = Auth::id();
        $carritoItems = Carrito::with('producto')->where('user_id', $userId)->get();

        if ($carritoItems->isEmpty()) {
            return back()->withErrors(['carrito' => 'El carrito está vacío']);
        }

        $request->validate([
            'metodo_pago' => 'required|in:yape,plin,transferencia,izipay,paypal',
            'comprobante_pago' => 'required|file|mimes:pdf|max:4096',
            'nombre' => 'required|string|max:191',
            'dni' => 'required|string|max:15',
            'razon_social' => 'nullable|string|max:191',
            'ruc' => 'nullable|string|max:15',
        ]);

        // Validar que el cliente suba el comprobante si es requerido
        if (in_array($request->metodo_pago, ['yape', 'plin', 'transferencia']) && !$request->hasFile('comprobante_pago')) {
            return back()->withErrors(['comprobante_pago' => 'Debes subir el comprobante de pago en PDF para completar la compra.']);
        }

        $total = 0;
        foreach ($carritoItems as $item) {
            $total += $item->cantidad * $item->producto->precio;
        }

        // Evitar órdenes duplicadas
        $ordenExistente = \App\Models\Orden::where('user_id', $userId)
            ->where('status', 'pendiente')
            ->where('metodo_pago', $request->metodo_pago)
            ->where('total', $total)
            ->first();

        if ($ordenExistente) {
            return back()->withErrors(['orden' => 'Ya tienes una orden pendiente con estos productos y método de pago.']);
        }

        // Validar stock
        foreach ($carritoItems as $item) {
            if ($item->cantidad > $item->producto->stock) {
                return back()->withErrors(['stock' => "El producto {$item->producto->nombre} no tiene suficiente stock"]);
            }
        }

        DB::beginTransaction();

        try {
            $comprobantePath = null;
            if ($request->hasFile('comprobante_pago')) {
                $comprobantePath = $request->file('comprobante_pago')->store('comprobantes', 'public');
            }
            $orden = Orden::create([
                'user_id' => $userId,
                'total' => $total,
                'status' => 'pendiente',
                'metodo_pago' => $request->metodo_pago,
                'comprobante_pago' => $comprobantePath,
                'razon_social' => $request->razon_social,
                'ruc' => $request->ruc,
                'nombre' => $request->nombre,
                'dni' => $request->dni,
            ]);

            // Emitir comprobante PDF automáticamente al crear la orden
            // (Simulación: genera un PDF simple con los datos de la orden y lo guarda en storage)
            $pdfPath = null;
            try {
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('pdf.comprobante', [
                    'orden' => $orden,
                    'carritoItems' => $carritoItems,
                    'user' => $orden->user,
                ]);
                $pdfPath = 'comprobantes/comprobante_orden_' . $orden->id . '.pdf';
                Storage::disk('public')->put($pdfPath, $pdf->output());
                $orden->comprobante_pago = $pdfPath;
                $orden->save();
            } catch (\Exception $e) {
                // Si falla la generación del PDF, continuar sin bloquear la orden
            }

            // Reducir stock
            foreach ($carritoItems as $item) {
                $producto = $item->producto;
                $producto->stock -= $item->cantidad;
                $producto->save();
            }

            // Vaciar carrito
            Carrito::where('user_id', $userId)->delete();

            DB::commit();

            return redirect()->route('ordenes.index')->with('success', 'Orden creada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al procesar la orden']);
        }
    }

    public function adminIndex(Request $request)
    {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403, 'Solo el administrador puede ver esta página.');
        }
        $query = \App\Models\Orden::with('user')->orderByDesc('created_at');
        if ($request->filled('cliente')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->cliente . '%');
            });
        }
        if ($request->filled('estado')) {
            $query->where('status', $request->estado);
        }
        if ($request->filled('fecha')) {
            $query->whereDate('created_at', $request->fecha);
        }
        $ordenes = $query->get();
        return view('dashboard.admin_ordenes', compact('ordenes'));
    }

    public function pasarelas()
    {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403, 'Solo el administrador puede ver esta página.');
        }
        $yape_numero = config('pasarelas.yape_numero', env('YAPE_NUMERO', ''));
        $yape_qr = config('pasarelas.yape_qr', env('YAPE_QR', ''));
        $plin_numero = config('pasarelas.plin_numero', env('PLIN_NUMERO', ''));
        $plin_qr = config('pasarelas.plin_qr', env('PLIN_QR', ''));
        $transferencia = config('pasarelas.transferencia', env('TRANSFERENCIA', ''));
        return view('dashboard.admin_pasarelas', compact('yape_numero', 'yape_qr', 'plin_numero', 'plin_qr', 'transferencia'));
    }

    public function guardarPasarelas(Request $request)
    {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403, 'Solo el administrador puede ver esta página.');
        }
        $data = $request->validate([
            'yape_numero' => 'nullable|string',
            'yape_qr' => 'nullable|image',
            'plin_numero' => 'nullable|string',
            'plin_qr' => 'nullable|image',
            'transferencia' => 'nullable|string',
        ]);
        // Guardar imágenes QR en storage y actualizar .env (solo ejemplo, en prod usar DB)
        if ($request->hasFile('yape_qr')) {
            $path = $request->file('yape_qr')->store('pasarelas', 'public');
            $data['yape_qr'] = $path;
            file_put_contents(base_path('.env'), str_replace(
                'YAPE_QR=' . env('YAPE_QR'),
                'YAPE_QR=' . $path,
                file_get_contents(base_path('.env'))
            ));
        }
        if ($request->hasFile('plin_qr')) {
            $path = $request->file('plin_qr')->store('pasarelas', 'public');
            $data['plin_qr'] = $path;
            file_put_contents(base_path('.env'), str_replace(
                'PLIN_QR=' . env('PLIN_QR'),
                'PLIN_QR=' . $path,
                file_get_contents(base_path('.env'))
            ));
        }
        // Guardar números y transferencia
        foreach (['yape_numero', 'plin_numero', 'transferencia'] as $campo) {
            if (isset($data[$campo])) {
                file_put_contents(base_path('.env'), str_replace(
                    strtoupper($campo) . '=' . env(strtoupper($campo)),
                    strtoupper($campo) . '=' . $data[$campo],
                    file_get_contents(base_path('.env'))
                ));
            }
        }
        return back()->with('success', 'Datos de pasarelas actualizados');
    }

    public function descargarComprobante(Orden $orden)
    {
        // Solo el dueño o el admin pueden descargar el comprobante
        if (auth()->id() !== $orden->user_id && !auth()->user()->is_admin) {
            abort(403);
        }
        if (!$orden->comprobante_pago) {
            return back()->withErrors(['comprobante' => 'No hay comprobante disponible para esta orden.']);
        }
        return response()->download(storage_path('app/public/' . $orden->comprobante_pago));
    }

    public function confirmarPaypal(Request $request)
    {
        // Aquí deberías validar el pago con la API de PayPal (webhook o fetch order)
        // Por simplicidad, solo marcamos la orden como pagada si recibimos el ID
        $ordenId = $request->input('orden_id');
        $paypalOrderId = $request->input('paypal_order_id');
        if (!$ordenId || !$paypalOrderId) {
            return response()->json(['success' => false, 'message' => 'Datos incompletos'], 400);
        }
        $orden = \App\Models\Orden::find($ordenId);
        if (!$orden) {
            return response()->json(['success' => false, 'message' => 'Orden no encontrada'], 404);
        }
        $orden->status = 'completado';
        $orden->save();
        // Aquí puedes guardar el ID de PayPal en la orden si lo deseas
        return response()->json(['success' => true]);
    }

    public function confirmarIzipay(Request $request)
    {
        // Aquí deberías validar el pago con la API de Izipay
        // Por simplicidad, solo marcamos la orden como pagada si recibimos el ID
        $ordenId = $request->input('orden_id');
        $izipayOrderId = $request->input('izipay_order_id');
        if (!$ordenId || !$izipayOrderId) {
            return response()->json(['success' => false, 'message' => 'Datos incompletos'], 400);
        }
        $orden = \App\Models\Orden::find($ordenId);
        if (!$orden) {
            return response()->json(['success' => false, 'message' => 'Orden no encontrada'], 404);
        }
        $orden->status = 'completado';
        $orden->save();
        // Aquí puedes guardar el ID de Izipay en la orden si lo deseas
        return response()->json(['success' => true]);
    }

    public function actualizarEstado(Request $request, Orden $orden)
    {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403);
        }
        $request->validate([
            'status' => 'required|in:pendiente,procesando,completado,cancelado',
        ]);
        $orden->status = $request->status;
        $orden->save();
        return back()->with('success', 'Estado de la orden actualizado');
    }

    public function validarComprobante(Request $request, Orden $orden)
    {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403);
        }
        $request->validate([
            'comprobante_validado' => 'required|boolean',
            'comentario_admin' => 'nullable|string|max:255',
        ]);
        $orden->comprobante_validado = $request->comprobante_validado;
        $orden->comentario_admin = $request->comentario_admin;
        $orden->save();
        return back()->with('success', 'Validación de comprobante actualizada');
    }
}
