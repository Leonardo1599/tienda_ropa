<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasarela;
use Illuminate\Support\Facades\Storage;

class PasarelaController extends Controller
{
    // Mostrar formulario de edición (solo admin)
    public function edit()
    {
        $pasarela = Pasarela::first();
        return view('dashboard.admin_pasarelas', compact('pasarela'));
    }

    // Guardar cambios (crear o actualizar)
    public function update(Request $request)
    {
        $pasarela = Pasarela::first() ?? new Pasarela();
        $pasarela->yape_numero = $request->yape_numero;
        $pasarela->plin_numero = $request->plin_numero;
        $pasarela->cuenta_transferencia = $request->cuenta_transferencia;

        if ($request->hasFile('yape_qr')) {
            if ($pasarela->yape_qr) Storage::disk('public')->delete($pasarela->yape_qr);
            $pasarela->yape_qr = $request->file('yape_qr')->store('qrs', 'public');
        }
        if ($request->hasFile('plin_qr')) {
            if ($pasarela->plin_qr) Storage::disk('public')->delete($pasarela->plin_qr);
            $pasarela->plin_qr = $request->file('plin_qr')->store('qrs', 'public');
        }
        $pasarela->save();

        return back()->with('success', 'Pasarelas actualizadas correctamente');
    }
}
