@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 600px;">
    <h2 class="mb-4 text-center">⚙️ Configuración de Pasarelas de Pago</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.pasarelas.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Número Yape</label>
                    <input type="text" name="yape_numero" class="form-control" value="{{ old('yape_numero', $pasarela->yape_numero ?? '') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">QR Yape</label>
                    @if($pasarela && $pasarela->yape_qr)
                        <div class="mb-2"><img src="{{ asset('storage/' . $pasarela->yape_qr) }}" alt="QR Yape" style="max-width:120px;"></div>
                    @endif
                    <input type="file" name="yape_qr" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                    <label class="form-label">Número Plin</label>
                    <input type="text" name="plin_numero" class="form-control" value="{{ old('plin_numero', $pasarela->plin_numero ?? '') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">QR Plin</label>
                    @if($pasarela && $pasarela->plin_qr)
                        <div class="mb-2"><img src="{{ asset('storage/' . $pasarela->plin_qr) }}" alt="QR Plin" style="max-width:120px;"></div>
                    @endif
                    <input type="file" name="plin_qr" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                    <label class="form-label">Cuenta de Transferencia</label>
                    <input type="text" name="transferencia" class="form-control" value="{{ old('transferencia', $pasarela->cuenta_transferencia ?? '') }}">
                </div>
                <div class="text-end">
                    <button class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
