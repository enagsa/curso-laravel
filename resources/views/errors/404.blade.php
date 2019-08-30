@extends('layout')

@section('title', 'Página no Encontrada')

@section('content')
	<div id="error-404">
		<span class="fa-stack fa-lg">
			<i class="fa fa-eye fa-stack-1x"></i>
			<i class="fa fa-ban fa-stack-2x"></i>
		</span>
		<h1>Página no encontrada</h1>

		<a href="{{ url('/') }}" class="volver">Volver a la Portada <i class="fa fa-undo"></i></a>
	</div>
@endsection