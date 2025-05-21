<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Orden</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        .total { text-align: right; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Comprobante de Orden #{{ $orden->id }}</h2>
        <p>Cliente: {{ $user->name }}<br>Email: {{ $user->email }}</p>
        <p>Fecha: {{ $orden->created_at->format('d/m/Y H:i') }}</p>
        <p><b>Tipo de comprobante:</b> {{ ($orden->ruc && $orden->razon_social) ? 'Factura' : 'Boleta' }}</p>
        @if($orden->razon_social || $orden->ruc)
            <p><b>Razón Social:</b> {{ $orden->razon_social ?? '-' }}<br><b>RUC:</b> {{ $orden->ruc ?? '-' }}</p>
        @endif
        <p><b>Nombre:</b> {{ $orden->nombre }}<br><b>DNI:</b> {{ $orden->dni }}</p>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($carritoItems as $item)
            <tr>
                <td>{{ $item->producto->nombre }}</td>
                <td>{{ $item->cantidad }}</td>
                <td>S/ {{ number_format($item->producto->precio, 2) }}</td>
                <td>S/ {{ number_format($item->cantidad * $item->producto->precio, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="total">Total: S/ {{ number_format($orden->total, 2) }}</div>
    <p>Método de pago: {{ ucfirst($orden->metodo_pago) }}</p>
</body>
</html>
