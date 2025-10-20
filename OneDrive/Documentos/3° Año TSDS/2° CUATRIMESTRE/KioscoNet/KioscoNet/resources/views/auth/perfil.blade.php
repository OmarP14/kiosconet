@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-user"></i> Mi Perfil
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('perfil.actualizar') }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo</label>
                                <input type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ old('nombre', $usuario->nombre) }}" 
                                       required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $usuario->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="usuario" 
                                       value="{{ $usuario->usuario }}" 
                                       readonly>
                                <small class="text-muted">El nombre de usuario no se puede cambiar</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rol" class="form-label">Rol</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="rol" 
                                       value="{{ ucfirst($usuario->rol) }}" 
                                       readonly>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5>Cambiar Contraseña</h5>
                    <small class="text-muted mb-3 d-block">Deja en blanco si no deseas cambiar la contraseña</small>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Nueva Contraseña</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Perfil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection