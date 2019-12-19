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
			@foreach($users as $user)
				<div class="table-line">
					<div class="table-cell">{{ $user->id }}</div>
					<div class="table-cell">
						<h3>{{ $user->name }}</h3>
						<div>Nombre de la Empresa</div>
						<div>{{ $user->profile->profession->title ?? '' }}</div>
					</div>
					<div class="table-cell">
						<h4>{{ $user->email }}</h4>
						<div class="skills">
							@foreach($user->skills as $skill)
								<span>{{ $skill->name }}</span>
							@endforeach
						</div>
					</div>
					<div class="table-cell">{{ $user->role }}</div>
					<div class="table-cell">
						<div><strong>Registro:</strong> <span>{{ $user->created_at }}</span></div>
						<div><strong>Último login:</strong> <span>{{ $user->updated_at }}</span></div>
					</div>
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