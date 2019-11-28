@extends('layout')

@section('title', 'Listado de Habilidades')

@section('content')
	<h1>Listado de Habilidades</h1>

	<a id="crear-usuario" class="volver" href="{{-- route('users.create') --}}">Nueva habilidad <i class="fa fa-plus"></i></a>

	@if($skills->isEmpty())
		<p>No hay habilidades registradas</p>
	@else
		<section id="profession-table" class="table">
			<div class="table-line title">
				<div class="table-cell">#</div>
				<div class="table-cell">Nombre</div>
				<div class="table-cell">Acciones</div>
			</div>
			@foreach($skills as $skill)
				<div class="table-line">
					<div class="table-cell">{{ $skill->id }}</div>
					<div class="table-cell">{{ $skill->name }}</div>
					<div class="table-cell">
						<a class="boton view" href="{{-- route('users.show', $user) --}}"><i class="fa fa-eye"></i></a>
						<a class="boton edit" href="{{-- route('users.edit', $user) --}}"><i class="fa fa-pencil"></i></a>
						<form action="{{-- route('users.destroy', $user) --}}" method="POST">
							{{ method_field('DELETE') }}
							{{ csrf_field() }}
							<button class="boton delete" type="submit"><i class="fa fa-trash"></i></button>
						</form>
					</div>
				</div>
			@endforeach
		</section>
	@endif
@endsection