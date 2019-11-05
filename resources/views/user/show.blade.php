@extends('layout')

@section('title', $user->name)

@section('content')
	<h1>{{ $user->name }}</h1>

	<a id="crear-usuario" class="volver" href="{{ route('users.edit', $user) }}">Editar <i class="fa fa-pencil"></i></a>
	<form id="borrar-usuario" action="{{ route('users.destroy', $user) }}" method="POST">
		{{ method_field('DELETE') }}
		{{ csrf_field() }}
		<button class="volver" type="submit">Borrar <i class="fa fa-trash"></i></button>
	</form>

	<section class="table user-details">
		<div class="table-line">
			<div class="table-cell"><strong>Nombre:</strong></div>
			<div class="table-cell">{{ $user->name }}</div>
		</div>
		<div class="table-line">
			<div class="table-cell"><strong>Correo Electrónico:</strong></div>
			<div class="table-cell">{{ $user->email }}</div>
		</div>
		@if($user->profession)
			<div class="table-line">
				<div class="table-cell"><strong>Profesión:</strong></div>
				<div class="table-cell">{{ $user->profile->profession->title }}</div>
			</div>
		@endif
	</section>
	<a href="{{ route('users') }}" class="volver">Volver al Listado <i class="fa fa-undo"></i></a>
@endsection