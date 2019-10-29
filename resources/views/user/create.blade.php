@extends('layout')

@section('title', 'Nuevo Usuario')

@section('content')
	<h1>Crear Nuevo Usuario</h1>

	@if($errors->any())
		<div class="errores">
			<p>Por favor corrige los errores</p>
		</div>
	@endif

	<form method="POST" action="{{ route('users.store') }}">
		{{ csrf_field() }}

		<div class="table user-details">
			<div class="table-line">
				<div class="table-cell"><label for="create-name"><strong>Nombre:</strong></label></div>
				<div class="table-cell">
					<input id="create-name" type="text" name="name" placeholder="Nombre Apellidos" value="{{ old('name') }}"/>
					@if($errors->has('name'))
						<span class="error">{{ $errors->first('name') }}</span>
					@endif
				</div>
			</div>
			<div class="table-line">
				<div class="table-cell"><label for="create-email"><strong>Correo Electrónico:</strong></label></div>
				<div class="table-cell">
					<input id="create-email" type="email" name="email" placeholder="email@dominio.ext" value="{{ old('email') }}"/>					
					@if($errors->has('email'))
						<span class="error">{{ $errors->first('email') }}</span>
					@endif
				</div>
			</div>
			<div class="table-line">
				<div class="table-cell"><label for="create-password"><strong>Contraseña:</strong></label></div>
				<div class="table-cell">
					<input id="create-password" type="password" name="password" placeholder="Mayor de 6 caracteres"/>					
					@if($errors->has('password'))
						<span class="error">{{ $errors->first('password') }}</span>
					@endif
				</div>
			</div>
			<div class="table-line align-top">
				<div class="table-cell"><label for="create-bio"><strong>Biografía:</strong></label></div>
				<div class="table-cell">
					<textarea id="create-bio" name="bio" placeholder="Cuéntanos algo de tu vida">{{ old('bio') }}</textarea>			
					@if($errors->has('bio'))
						<span class="error">{{ $errors->first('bio') }}</span>
					@endif
				</div>
			</div>
			<div class="table-line align-top">
				<div class="table-cell"><label for="create-profesion"><strong>Profesión:</strong></label></div>
				<div class="table-cell">
					<select name="profession_id" id="create-profesion">
						<option value="">Selecciona una profesión</option>
						@foreach($professions as $profession)
							<option value="{{ $profession->id }}"{{ old('profession_id') == $profession->id ? ' selected' : '' }}>{{ $profession->title }}</option>
						@endforeach
					</select>		
					@if($errors->has('profession_id'))
						<span class="error">{{ $errors->first('profession_id') }}</span>
					@endif
				</div>
			</div>
			<div class="table-line">
				<div class="table-cell"><label for="create-twitter"><strong>Twitter:</strong></label></div>
				<div class="table-cell">
					<input id="create-twitter" type="text" name="twitter" placeholder="https://twitter.com/usuario" value="{{ old('twitter') }}"/>					
					@if($errors->has('twitter'))
						<span class="error">{{ $errors->first('twitter') }}</span>
					@endif
				</div>
			</div>
		</div>

		<button type="submit" class="volver">Crear usuario <i class="fa fa-plus"></i></button>
		<a href="{{ route('users') }}" class="volver">Volver al Listado <i class="fa fa-undo"></i></a>
	</form>
@endsection