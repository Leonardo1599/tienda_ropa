{{-- filepath: resources/views/dashboard/ordenes.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">
        📦 {{ auth()->user()->is_admin ? 'Órdenes de Clientes' : 'Mis Órdenes' }}
    </h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            @if(auth()->user()->is_admin)
                                <th>Cliente</th>
                                <th>Email</th>
                            @endif
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Método de Pago</th>
                            <th>Comprobante</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($ordenes as $orden)
                        <tr>
                            <td>{{ $orden->id }}</td>
                            @if(auth()->user()->is_admin)
                                <td>{{ $orden->user->name }}</td>
                                <td>{{ $orden->user->email }}</td>
                            @endif
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
                            <td>
                                @if($orden->comprobante_pago)
                                    <a href="{{ route('ordenes.comprobante', $orden) }}" target="_blank" class="btn btn-sm btn-outline-info">Descargar PDF</a>
                                @else
                                    <span class="text-muted">No subido</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->is_admin ? 8 : 6 }}" class="text-center text-muted">
                                {{ auth()->user()->is_admin ? 'No hay órdenes registradas.' : 'No tienes órdenes registradas.' }}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <a href="{{ route('dashboard.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver a productos
        </a>
    </div>
</div>
@endsection