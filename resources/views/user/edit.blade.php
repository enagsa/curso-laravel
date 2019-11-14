@extends('layout')

@section('title', 'Editando Usuario '.$user->id)

@section('content')
	<h1>Editar Usuario #{{ $user->id }}</h1>

	@include('shared._errors')

	<form method="POST" action="{{ route('users.update', compact('user')) }}">
		{{ method_field('PUT') }}

		<div class="table user-details">
			@include('user._fields')
		</div>

		<button type="submit" class="volver">Actualizar usuario <i class="fa fa-pencil"></i></button>
		<a href="{{ route('users') }}" class="volver">Volver al Listado <i class="fa fa-undo"></i></a>
	</form>
@endsection