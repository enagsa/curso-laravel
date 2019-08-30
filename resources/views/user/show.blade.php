@extends('layout')

@section('title', 'Usuario '.$user->id)

@section('content')
	<h1>{{ $user->name }} (#{{ $user->id }})</h1>
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
				<div class="table-cell">{{ $user->profession->title }}</div>
			</div>
		@endif
	</section>
	<a href="{{ route('users') }}" class="volver">Volver al Listado <i class="fa fa-undo"></i></a>
@endsection