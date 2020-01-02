@extends('layout')

@section('title', 'Listado de Usuarios')

@section('content')
	<h1>{{ $title }}</h1>

	<a id="crear-usuario" class="volver" href="{{ route('users.create') }}">Nuevo usuario <i class="fa fa-plus"></i></a>

	@if($users->isEmpty())
		<p>No hay usuarios registrados</p>
	@else
		<form id="filters" method="GET" action="{{ route('users') }}">
			<div id="toggle-users">
				<label for="users-all"><input type="radio" id="users-all" name="users" value="all" @if(old('users') == 'all') checked='checked' @endif/> Todos</label>
				<label for="users-active"><input type="radio" id="users-active" name="users" value="active" @if(old('users') == 'active') checked='checked' @endif/> Solo activos</label>
				<label for="users-innactive"><input type="radio" id="users-innactive" name="users" value="innactive" @if(old('users') == 'innactive') checked='checked' @endif/> Solo inactivos</label>
			</div>
			<div id="filters-left">
				<div id="search-box">
					<input type="text" name="search" placeholder="Buscar..." value="{{ old('search') }}">
					<button id="search-button"><i class="fa fa-search"></i></button>
				</div>
				<select name="role" id="role-select">
					<option value="">Roles</option>
					@foreach($roles as $role => $name)
						<option value="{{ $role }}" @if(old('role') == $role) selected="selected" @endif>{{ $name }}</option>
					@endforeach
				</select>
				<select name="skill" id="skill-select">
					<option value="">Habilidades</option>
					@foreach($skills as $skill)
						<option value="{{ $skill->id }}">{{ $skill->name }}</option>
					@endforeach
				</select>
				<div style="clear:both"></div>
			</div>
			<div id="filters-right">
				<label for="date-start">Desde:</label>
				<input type="date" name="date-start" id="date-start" value="old('date-start')" placeholder="Desde">
				<label for="date-end">Hasta:</label>
				<input type="date" name="date-end" id="date-end" value="old('date-end')" placeholder="Hasta">
				<input type="submit" id="filter" value="Filtrar"/>
			</div>
			<div style="clear:both"></div>
		</form>
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
		{{ $users->links() }}
	@endif
@endsection