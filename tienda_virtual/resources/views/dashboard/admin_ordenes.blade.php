@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">📋 Órdenes de Clientes</h2>
    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-3">
            <input type="text" name="cliente" class="form-control" placeholder="Buscar cliente..." value="{{ request('cliente') }}">
        </div>
        <div class="col-md-3">
            <select name="estado" class="form-select">
                <option value="">Todos los estados</option>
                <option value="pendiente" @if(request('estado')=='pendiente') selected @endif>Pendiente</option>
                <option value="procesando" @if(request('estado')=='procesando') selected @endif>Procesando</option>
                <option value="completado" @if(request('estado')=='completado') selected @endif>Completado</option>
                <option value="cancelado" @if(request('estado')=='cancelado') selected @endif>Cancelado</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" name="fecha" class="form-control" value="{{ request('fecha') }}">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Filtrar</button>
        </div>
    </form>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Email</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Método de Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($ordenes as $orden)
                        <tr>
                            <td>{{ $orden->id }}</td>
                            <td>{{ $orden->user->name ?? '-' }}</td>
                            <td>{{ $orden->user->email ?? '-' }}</td>
                            <td>S/ {{ number_format($orden->total, 2) }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($orden->status) }}</span></td>
                            <td>{{ $orden->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($orden->metodo_pago === 'yape')
                                    <span class="badge bg-success">Yape</span>
                                @elseif($orden->metodo_pago === 'plin')
                                    <span class="badge bg-primary">Plin</span>
                                @elseif($orden->metodo_pago === 'transferencia')
                                    <span class="badge bg-warning text-dark">Transferencia</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay órdenes registradas.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
