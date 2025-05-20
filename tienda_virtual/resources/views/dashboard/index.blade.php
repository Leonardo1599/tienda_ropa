@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">🛍️ Productos</h2>
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

    @php($categorias = \App\Models\Producto::categoriasRopa())

    @if(auth()->user() && auth()->user()->is_admin)
        <!-- Botón para abrir el modal -->
        <div class="mb-4">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto">
                <i class="bi bi-plus-circle"></i> Agregar Producto
            </button>
        </div>
        <!-- Modal de agregar producto -->
        <div class="modal fade" id="modalAgregarProducto" tabindex="-1" aria-labelledby="modalAgregarProductoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarProductoLabel">Agregar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-2">
                                <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="descripcion" class="form-control" placeholder="Descripción">
                            </div>
                            <div class="mb-2">
                                <input type="number" name="precio" step="0.01" class="form-control" placeholder="Precio" required>
                            </div>
                            <div class="mb-2">
                                <input type="number" name="stock" class="form-control" placeholder="Stock" required>
                            </div>
                            <div class="mb-2">
                                <select name="categoria" class="form-select" required>
                                    <option value="">Selecciona categoría</option>
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <input type="file" name="imagen" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Agregar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @foreach($categorias as $cat)
        @php($productosCat = $productos->where('categoria', $cat))
        @if($productosCat->count())
            <div class="mb-2 mt-5">
                <h4 class="text-primary border-bottom pb-1">{{ $cat }}</h4>
            </div>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4 mb-4">
                @foreach($productosCat as $producto)
                    <div class="col d-flex align-items-stretch">
                        <div class="card h-100 shadow-sm w-100 d-flex flex-column">
                            @if($producto->imagen)
                                <img src="{{ Str::startsWith($producto->imagen, 'http') ? $producto->imagen : asset('storage/' . $producto->imagen) }}" class="card-img-top" alt="{{ $producto->nombre }}" style="object-fit:cover;height:220px;">
                            @else
                                <img src="https://loremflickr.com/320/220/clothes?lock={{ $producto->id }}" class="card-img-top" alt="Imagen por defecto" style="object-fit:cover;height:220px;">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $producto->nombre }}</h5>
                                <p class="card-text small text-muted mb-1">{{ $producto->descripcion }}</p>
                                <div class="mb-2"><span class="badge bg-secondary">{{ $producto->categoria }}</span></div>
                                <div class="fw-bold fs-5 mb-2">S/ {{ number_format($producto->precio, 2) }}</div>
                                <div class="mt-auto">
                                    @if(!auth()->user() || !auth()->user()->is_admin)
                                    <form method="POST" action="{{ route('carrito.store') }}">
                                        @csrf
                                        <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                        <div class="input-group input-group-sm mb-2">
                                            <input type="number" name="cantidad" min="1" max="{{ $producto->stock }}" value="1" class="form-control" style="max-width:80px;">
                                            <button class="btn btn-primary" type="submit">Agregar al carrito</button>
                                        </div>
                                    </form>
                                    @endif
                                    @if(auth()->user() && auth()->user()->is_admin)
                                    <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('¿Eliminar este producto?')"><i class="bi bi-trash"></i> Eliminar</button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach
</div>
@endsection
