@extends('layout')

@section('title', 'Nuevo Usuario')

@section('content')
	<h1>Crear Nuevo Usuario</h1>

	@include('shared._errors')

	@component('shared._form')
		@slot('action', route('users.store'))
		@slot('customs', '')
		@slot('fields')
			@include('user._fields')
		@endslot
		@slot('button')
			Crear usuario <i class="fa fa-plus"></i>
		@endslot
	@endcomponent
@endsection