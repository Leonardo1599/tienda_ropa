{{-- filepath: resources/views/dashboard/carrito.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">🛒 Mi Carrito de Compras</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if($carrito->isEmpty())
                <div class="p-4 text-center text-muted">Tu carrito está vacío.</div>
            @else
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carrito as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->producto->nombre }}</strong><br>
                                <small class="text-muted">{{ $item->producto->descripcion }}</small>
                            </td>
                            <td>S/ {{ number_format($item->producto->precio, 2) }}</td>
                            <td>
                                <form action="{{ route('carrito.update', $item) }}" method="POST" class="d-flex align-items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="cantidad" value="{{ $item->cantidad }}" min="1" max="{{ $item->producto->stock }}" class="form-control form-control-sm" style="width:70px;">
                                    <button class="btn btn-sm btn-outline-primary" title="Actualizar"><i class="bi bi-arrow-repeat"></i></button>
                                </form>
                            </td>
                            <td>S/ {{ number_format($item->cantidad * $item->producto->precio, 2) }}</td>
                            <td>
                                <form action="{{ route('carrito.destroy', $item) }}" method="POST" onsubmit="return confirm('¿Eliminar este producto del carrito?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 d-flex justify-content-between align-items-center bg-light border-top">
                <div>
                    <span class="fw-bold">Total:</span> <span class="fs-5 text-success">S/ {{ number_format($total, 2) }}</span>
                </div>
            </div>
            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <h5 class="card-title p-3">Método de Pago</h5>
                    <form action="{{ route('ordenes.store') }}" method="POST" class="row g-3 align-items-center" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-4">
                            <select name="metodo_pago" class="form-select" id="metodo_pago" required>
                                <option value="">Selecciona método de pago</option>
                                <option value="yape">Yape</option>
                                <option value="plin">Plin</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="izipay">Izipay</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>
                        <div class="col-md-8" id="pago-instruccion" style="display:none;">
                            <div id="pago-info"></div>
                            <div id="qr-img" class="mt-2"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="razon_social" class="form-label">Razón Social (opcional)</label>
                            <input type="text" name="razon_social" id="razon_social" class="form-control" maxlength="191">
                        </div>
                        <div class="col-md-6">
                            <label for="ruc" class="form-label">RUC (opcional)</label>
                            <input type="text" name="ruc" id="ruc" class="form-control" maxlength="15">
                        </div>
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre completo <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="191" required>
                        </div>
                        <div class="col-md-6">
                            <label for="dni" class="form-label">DNI <span class="text-danger">*</span></label>
                            <input type="text" name="dni" id="dni" class="form-control" maxlength="15" required>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="comprobante_pago" class="form-label">Comprobante de pago (PDF) <span class="text-danger">*</span></label>
                            <input type="file" name="comprobante_pago" id="comprobante_pago" class="form-control" accept="application/pdf" required>
                            <small class="text-danger">Obligatorio: Sube tu comprobante de pago en PDF (máx 4MB).</small>
                        </div>
                        <div class="col-md-12">
                            <div id="tipo-comprobante" class="alert alert-info p-2 mb-2" style="display:none;"></div>
                        </div>
                        <div class="col-md-12" id="paypal-btn-container" style="display:none;"></div>
                        <div class="col-md-12" id="izipay-btn-container" style="display:none;"></div>
                        <div class="col-md-12 mt-3">
                            <button class="btn btn-success btn-lg w-100">Finalizar compra <i class="bi bi-bag-check"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="mt-4">
        <a href="{{ route('dashboard.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Seguir comprando</a>
    </div>
</div>
<script src="https://www.paypal.com/sdk/js?client-id=Afl9yTBjVCHzB2bSP80f5zRj4ykDQY1uNKTwX5Z34izsAPYLSqI5jiUEc9WuGP8YACIq34s0lDEJUxD1&currency=USD"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('metodo_pago');
        const infoDiv = document.getElementById('pago-info');
        const instruccionDiv = document.getElementById('pago-instruccion');
        const qrDiv = document.getElementById('qr-img');
        const paypalDiv = document.getElementById('paypal-btn-container');
        const izipayDiv = document.getElementById('izipay-btn-container');
        function mostrarQR(metodo) {
            let html = '';
            let qr = '';
            paypalDiv.style.display = 'none';
            izipayDiv.style.display = 'none';
            if(metodo === 'yape') {
                html = `<b>Yape:</b><br>`;
                html += `{!! $yape_numero ? '<span class=\"text-primary\">Número: ' . $yape_numero . '</span><br>' : '<span class=\"text-muted\">Número: No disponible</span><br>' !!}`;
                @if($yape_numero && $total)
                    // QR dinámico con Google Chart API (Yape)
                    let yapeData = `00020101021126360016A0000006770101120115{{$yape_numero}}520400005303604054{{$total}}5802PE5920Tienda Virtual6033Pago de orden en tienda virtual6304`;
                    let qrUrl = `https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=${encodeURIComponent(yapeData)}`;
                    qr = `<img src='${qrUrl}' alt='QR Yape' style='max-width:150px;'>`;
                @elseif($yape_qr)
                    qr = `<img src='{{ asset('storage/' . $yape_qr) }}' alt='QR Yape' style='max-width:150px;'>`;
                @else
                    qr = `<span class='text-muted'>QR: No disponible</span>`;
                @endif
            } else if(metodo === 'plin') {
                html = `<b>Plin:</b><br>`;
                html += `{!! $plin_numero ? '<span class=\"text-primary\">Número: ' . $plin_numero . '</span><br>' : '<span class=\"text-muted\">Número: No disponible</span><br>' !!}`;
                @if($plin_numero && $total)
                    // QR dinámico con Google Chart API (Plin)
                    let plinData = `00020101021126360016A0000006770101120115{{$plin_numero}}520400005303604054{{$total}}5802PE5920Tienda Virtual6033Pago de orden en tienda virtual6304`;
                    let qrUrl = `https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=${encodeURIComponent(plinData)}`;
                    qr = `<img src='${qrUrl}' alt='QR Plin' style='max-width:150px;'>`;
                @elseif($plin_qr)
                    qr = `<img src='{{ asset('storage/' . $plin_qr) }}' alt='QR Plin' style='max-width:150px;'>`;
                @else
                    qr = `<span class='text-muted'>QR: No disponible</span>`;
                @endif
            } else if(metodo === 'izipay') {
                html = `<b>Izipay:</b><br><span class='text-primary'>Pago con tarjeta débito/crédito.</span>`;
                qr = '';
                izipayDiv.style.display = 'block';
                izipayDiv.innerHTML = '<button class="btn btn-warning">Pagar con Izipay (demo)</button>';
            } else if(metodo === 'paypal') {
                html = `<b>PayPal:</b><br><span class='text-primary'>Serás redirigido a PayPal para completar el pago.</span>`;
                qr = '';
                paypalDiv.style.display = 'block';
                paypalDiv.innerHTML = '';
                paypal.Buttons({
                    createOrder: function(data, actions) {
                        return actions.order.create({
                            purchase_units: [{
                                amount: { value: '{{ $total }}' }
                            }]
                        });
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(details) {
                            // Llama a la API backend para marcar la orden como pagada
                            fetch('/api/paypal/confirm', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                },
                                body: JSON.stringify({
                                    paypal_order_id: data.orderID
                                })
                            })
                            .then(res => res.json())
                            .then(res => {
                                if(res.success) {
                                    alert('Pago realizado y confirmado.');
                                    window.location.href = '/ordenes';
                                } else {
                                    alert('Error al confirmar el pago: ' + res.message);
                                }
                            });
                        });
                    }
                }).render('#paypal-btn-container');
            } else if(metodo === 'transferencia') {
                html = `<b>Transferencia:</b><br>`;
                html += `{!! $cuenta_transferencia ? '<span class=\"text-primary\">Cuenta: ' . $cuenta_transferencia . '</span>' : '<span class=\"text-muted\">Cuenta: No disponible</span>' !!}`;
                qr = '';
            }
            infoDiv.innerHTML = html;
            qrDiv.innerHTML = qr;
            instruccionDiv.style.display = html ? 'block' : 'none';
        }
        if(select) {
            select.addEventListener('change', function() {
                mostrarQR(this.value);
            });
            if(select.value) mostrarQR(select.value);
        }

        function actualizarTipoComprobante() {
            const razon = document.getElementById('razon_social').value.trim();
            const ruc = document.getElementById('ruc').value.trim();
            const tipoDiv = document.getElementById('tipo-comprobante');
            if (ruc.length === 11 && razon.length > 0) {
                tipoDiv.textContent = 'Se emitirá: Factura';
                tipoDiv.style.display = 'block';
            } else {
                tipoDiv.textContent = 'Se emitirá: Boleta';
                tipoDiv.style.display = 'block';
            }
        }
        const razon = document.getElementById('razon_social');
        const ruc = document.getElementById('ruc');
        if (razon && ruc) {
            razon.addEventListener('input', actualizarTipoComprobante);
            ruc.addEventListener('input', actualizarTipoComprobante);
            actualizarTipoComprobante();
        }
    });
</script>
@endsection