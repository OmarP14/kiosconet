@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
{{-- Reutilizamos el formulario de create.blade.php --}}
@include('clientes.create')
@endsection