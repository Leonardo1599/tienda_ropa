<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tienda de Ropa Premium')</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts para tipografía elegante -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-principal: #6a11cb;
            --color-secundario: #2575fc;
            --color-amarillo: #ffe066;
            --color-violeta: #a259c6;
            --color-azul-suave: #e3f0ff;
            --color-fondo: #f6f7fb;
            --color-footer: #2d2d44;
        }
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background-color: var(--color-fondo);
        }
        .navbar {
            background: linear-gradient(90deg, var(--color-principal) 0%, var(--color-secundario) 100%);
        }
        .navbar-brand, .navbar-nav .nav-link, .navbar-light .navbar-nav .nav-link {
            color: #fff !important;
        }
        .navbar-brand {
            font-weight: bold;
            letter-spacing: 2px;
            font-size: 1.5rem;
        }
        .navbar-nav .nav-link.active, .navbar-nav .nav-link:focus, .navbar-nav .nav-link:hover {
            color: var(--color-amarillo) !important;
        }
        .product-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(106,17,203,0.07);
            transition: transform 0.2s, box-shadow 0.2s;
            background: #fff;
            border-top: 4px solid var(--color-principal);
        }
        .product-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 8px 32px rgba(37,117,252,0.12);
            border-top: 4px solid var(--color-amarillo);
        }
        .product-img {
            border-radius: 18px 18px 0 0;
            object-fit: cover;
            height: 320px;
            background: var(--color-azul-suave);
        }
        .btn-primary {
            background: var(--color-principal) !important;
            border: none;
            color: #fff;
        }
        .btn-primary:hover, .btn-primary:focus {
            background: var(--color-violeta) !important;
            color: #fff;
        }
        .btn-success {
            background: var(--color-secundario) !important;
            border: none;
        }
        .btn-success:hover, .btn-success:focus {
            background: var(--color-principal) !important;
        }
        .btn-warning {
            background: var(--color-amarillo) !important;
            color: #2d2d44 !important;
            border: none;
        }
        .btn-outline-primary {
            border-color: var(--color-principal) !important;
            color: var(--color-principal) !important;
        }
        .btn-outline-primary:hover {
            background: var(--color-principal) !important;
            color: #fff !important;
        }
        .table thead {
            background: linear-gradient(90deg, var(--color-principal) 0%, var(--color-secundario) 100%);
            color: #fff;
        }
        .table tbody tr:hover {
            background: var(--color-azul-suave);
        }
        .card-title, h1, h2, h3, h4, h5 {
            color: var(--color-principal);
        }
        .bg-light {
            background-color: #f9f7fd !important;
        }
        .form-label {
            color: var(--color-violeta);
            font-weight: 600;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--color-principal);
            box-shadow: 0 0 0 0.2rem rgba(106,17,203,0.15);
        }
        .payment-form-wrapper, .w-100.px-2.px-sm-4.px-md-5.py-3.mx-auto {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(106,17,203,0.07);
        }
        footer {
            background: var(--color-footer);
            color: #ffe066;
            padding: 32px 0 16px 0;
            margin-top: 48px;
        }
        footer small {
            color: #fff;
        }
        .alert-info {
            background: var(--color-azul-suave);
            color: var(--color-principal);
            border: none;
        }
        .text-success {
            color: var(--color-principal) !important;
        }
        .btn-outline-secondary {
            border-color: var(--color-violeta) !important;
            color: var(--color-violeta) !important;
        }
        .btn-outline-secondary:hover {
            background: var(--color-violeta) !important;
            color: #fff !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Tienda de Ropa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    {{-- ...aquí puedes agregar enlaces de navegación como Categorías, Carrito, etc... --}}
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Registrarse</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('carrito.index') }}">Carrito</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">{{ Auth::user()->name }}</a></li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="nav-link btn btn-link" type="submit">Salir</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>

    <footer class="text-center">
        <div class="container">
            <p class="mb-1">© {{ date('Y') }} Tienda de Ropa Premium. Todos los derechos reservados.</p>
            <small>Desarrollado con ❤️ por tu equipo.</small>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
