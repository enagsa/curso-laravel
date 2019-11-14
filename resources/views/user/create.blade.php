@extends('layout')

@section('title', 'Nuevo Usuario')

@section('content')
	<h1>Crear Nuevo Usuario</h1>

	@include('shared._errors')

	<form method="POST" action="{{ route('users.store') }}">
		<div class="table user-details">
			@include('user._fields')
		</div>

		<button type="submit" class="volver">Crear usuario <i class="fa fa-plus"></i></button>
		<a href="{{ route('users') }}" class="volver">Volver al Listado <i class="fa fa-undo"></i></a>
	</form>
@endsection