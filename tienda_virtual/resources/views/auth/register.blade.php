@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 400px;">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="mb-4 text-center">Registro</h2>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ url('/register') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="name" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Registrar</button>
            </form>
            <div class="mt-3 text-center">
                <a href="{{ url('/login') }}">¿Ya tienes cuenta? Entrar</a>
            </div>
        </div>
    </div>
</div>
@endsection
