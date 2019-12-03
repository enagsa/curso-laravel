@extends('layout')

@section('title', 'Listado de Profesiones')

@section('content')
	<h1>Listado de Profesiones</h1>

	<a id="crear-usuario" class="volver" href="{{-- route('users.create') --}}">Nueva profesión <i class="fa fa-plus"></i></a>

	@if($professions->isEmpty())
		<p>No hay profesiones registradas</p>
	@else
		<section id="profession-table" class="table">
			<div class="table-line title">
				<div class="table-cell">#</div>
				<div class="table-cell">Nombre</div>
				<div class="table-cell">Perfiles</div>
				<div class="table-cell">Acciones</div>
			</div>
			@foreach($professions as $profession)
				<div class="table-line">
					<div class="table-cell">{{ $profession->id }}</div>
					<div class="table-cell">{{ $profession->title }}</div>
					<div class="table-cell">{{ $profession->profiles_count }}</div>
					<div class="table-cell">
						<a class="boton view" href="{{-- route('users.show', $user) --}}"><i class="fa fa-eye"></i></a>
						<a class="boton edit" href="{{-- route('users.edit', $user) --}}"><i class="fa fa-pencil"></i></a>
						@if($profession->profiles_count==0)
						<form action="{{ route('profession.delete', $profession) }}" method="POST">
							@csrf
							@method('DELETE')
							<button class="boton delete" type="submit"><i class="fa fa-trash"></i></button>
						</form>
						@endif
					</div>
				</div>
			@endforeach
		</section>
	@endif
@endsection