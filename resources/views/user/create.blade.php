@extends('layout')

@section('title', 'Nuevo Usuario')

@section('content')
	<h1>Crear Nuevo Usuario</h1>

	<form method="POST" action="{{ route('users.store') }}" class="table user-details">
		{{ csrf_field() }}
		<button type="submit" class="volver">Crear usuario <i class="fa fa-plus"></i></button>
		<a href="{{ route('users') }}" class="volver">Volver al Listado <i class="fa fa-undo"></i></a>
	</form>
@endsection