@extends('layout')

@section('title', 'Editando Usuario '.$user->id)

@section('content')
	<h1>Editando detalles del usuario: {{ $user->id }}</h1>
@endsection