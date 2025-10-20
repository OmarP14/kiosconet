@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
{{-- Reutilizamos el formulario de create.blade.php pasando $producto --}}
@include('productos.create')
@endsection