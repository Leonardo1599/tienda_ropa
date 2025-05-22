@extends('layouts.app')

@section('title', 'Catálogo de Ropa')

@section('content')
<h1 class="mb-4 text-center fw-bold">Catálogo de Ropa</h1>
<div class="row g-4">
    @foreach($productos as $producto)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card product-card h-100">
                <img src="{{ asset('images/productos/' . $producto->imagen) }}" class="card-img-top product-img" alt="{{ $producto->nombre }}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold">{{ $producto->nombre }}</h5>
                    <p class="card-text text-muted mb-2">{{ $producto->descripcion }}</p>
                    <div class="mt-auto">
                        <span class="fw-bold fs-5 text-primary">S/ {{ number_format($producto->precio, 2) }}</span>
                        <a href="{{ route('carrito.agregar', $producto->id) }}" class="btn btn-primary w-100 mt-2">Agregar al carrito</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
