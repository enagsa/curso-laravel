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
						@if($user->trashed())
							<form action="{{ route('users.restore', $user->id) }}" method="POST">
								@csrf
								@method('PATCH')
								<button class="boton edit" type="submit"><i class="fa fa-undo"></i></button>
							</form>
							<form action="{{ route('users.destroy', $user->id) }}" method="POST">
								@csrf
								@method('DELETE')
								<button class="boton delete" type="submit"><i class="fa fa-times"></i></button>
							</form>
						@else
							<a class="boton view" href="{{ route('users.show', $user) }}"><i class="fa fa-eye"></i></a>
							<a class="boton edit" href="{{ route('users.edit', $user) }}"><i class="fa fa-pencil"></i></a>
							<form action="{{ route('users.trash', $user) }}" method="POST">
								@csrf
								@method('PATCH')
								<button class="boton delete" type="submit"><i class="fa fa-trash"></i></button>
							</form>
						@endif
					</div>
				</div>
			@endforeach
		</section>
		{{ $users->links() }}
	@endif
@endsection