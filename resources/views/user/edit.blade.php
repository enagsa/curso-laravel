@extends('layout')

@section('title', 'Editando Usuario '.$user->id)

@section('content')
	<h1>Editar Usuario #{{ $user->id }}</h1>

	@include('shared._errors')

	@component('shared._form')
		@slot('action', route('users.update', $user))
		@slot('customs')
			{{ method_field('PUT') }}
		@endslot
		@slot('fields')
			@render('UserFields', compact('user'))
		@endslot
		@slot('button')
			Actualizar usuario <i class="fa fa-pencil"></i>
		@endslot
	@endcomponent
@endsection