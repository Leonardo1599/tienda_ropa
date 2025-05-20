<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito = Carrito::with('producto')->where('user_id', auth()->id())->get();
        $total = $carrito->sum(fn($item) => $item->cantidad * $item->producto->precio);

        // Supón que solo hay una fila de pasarelas configurada por el admin
        $pasarela = \App\Models\Pasarela::first();

        return view('dashboard.carrito', [
            'carrito' => $carrito,
            'total' => $total,
            'yape_numero' => $pasarela->yape_numero ?? null,
            'yape_qr' => $pasarela->yape_qr ?? null,
            'plin_numero' => $pasarela->plin_numero ?? null,
            'plin_qr' => $pasarela->plin_qr ?? null,
            'cuenta_transferencia' => $pasarela->cuenta_transferencia ?? null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        if ($request->cantidad > $producto->stock) {
            return back()->withErrors(['cantidad' => 'Cantidad supera stock disponible']);
        }

        $carrito = Carrito::where('user_id', Auth::id())->where('producto_id', $producto->id)->first();

        if ($carrito) {
            $nuevaCantidad = $carrito->cantidad + $request->cantidad;
            if ($nuevaCantidad > $producto->stock) {
                return back()->withErrors(['cantidad' => 'Cantidad supera stock disponible']);
            }
            $carrito->update(['cantidad' => $nuevaCantidad]);
        } else {
            Carrito::create([
                'user_id' => Auth::id(),
                'producto_id' => $producto->id,
                'cantidad' => $request->cantidad,
            ]);
        }

        return back()->with('success', 'Producto agregado al carrito');
    }

    public function update(Request $request, Carrito $carrito)
    {
        $this->authorize('update', $carrito);

        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = $carrito->producto;

        if ($request->cantidad > $producto->stock) {
            return back()->withErrors(['cantidad' => 'Cantidad supera stock disponible']);
        }

        $carrito->update(['cantidad' => $request->cantidad]);
        return back()->with('success', 'Carrito actualizado');
    }

    public function destroy(Carrito $carrito)
    {
        $this->authorize('delete', $carrito);
        $carrito->delete();
        return back()->with('success', 'Producto removido del carrito');
    }
}
