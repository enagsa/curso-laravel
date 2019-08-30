@extends('layout')

@section('title', 'Listado de Usuarios')

@section('content')
	<h1>{{ $title }}</h1>

	<a id="crear-usuario" class="volver" href="{{ route('users.create') }}">Nuevo usuario <i class="fa fa-plus"></i></a>

	@if($users->isEmpty())
		<p>No hay usuarios registrados</p>
	@else
		<section class="table">
			<div class="table-line title">
				<div class="table-cell">Nombre</div>
				<div class="table-cell">Email</div>
				<div class="table-cell">Acciones</div>
			</div>
			@foreach($users as $user)
				<div class="table-line">
					<div class="table-cell">{{ $user->name }}</div>
					<div class="table-cell">{{ $user->email }}</div>
					<div class="table-cell">
						<a class="boton view" href="{{ route('users.show', ['user' => $user]) }}"><i class="fa fa-eye"></i></a>
						<a class="boton edit" href="#"><i class="fa fa-pencil"></i></a>
						<a class="boton delete" href="#"><i class="fa fa-trash"></i></a>
					</div>
				</div>
			@endforeach
		</section>
	@endif
@endsection