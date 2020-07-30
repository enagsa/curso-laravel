@extends('layout')

@section('title', 'Listado de Usuarios')

@section('content')
	<h1>{{ $title }}</h1>

	<a id="crear-usuario" class="volver" href="{{ route('users.create') }}">Nuevo usuario <i class="fa fa-plus"></i></a>

	@include('user._filters')

	@if($users->isEmpty())
		<p>No hay usuarios registrados</p>
	@else

		<section id="users-table" class="table">
			<div class="table-line title">
				<div class="table-cell">#</div>
				<div class="table-cell">Nombre</div>
				<div class="table-cell">Email</div>
				<div class="table-cell">Rol</div>
				<div class="table-cell">Fechas</div>
				<div class="table-cell">Acciones</div>
			</div>
			
			@each('user._row', $users, 'user')
		</section>
		{{ $users->appends(request(['search','team']))->links() }}
	@endif
@endsection