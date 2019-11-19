{{ csrf_field() }}
<div class="table-line">
	<div class="table-cell"><label for="create-name"><strong>Nombre:</strong></label></div>
	<div class="table-cell">
		<input id="create-name" type="text" name="name" placeholder="Nombre Apellidos" value="{{ old('name', $user->name) }}"/>
		@if($errors->has('name'))
			<span class="error">{{ $errors->first('name') }}</span>
		@endif
	</div>
</div>
<div class="table-line">
	<div class="table-cell"><label for="create-email"><strong>Correo Electrónico:</strong></label></div>
	<div class="table-cell">
		<input id="create-email" type="email" name="email" placeholder="email@dominio.ext" value="{{ old('email', $user->email) }}"/>					
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
		<textarea id="create-bio" name="bio" placeholder="Cuéntanos algo de tu vida">{{ old('bio', $user->profile->bio) }}</textarea>			
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
				<option value="{{ $profession->id }}"{{ old('profession_id', $user->profile->profession_id) == $profession->id ? ' selected' : '' }}>{{ $profession->title }}</option>
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
		<input id="create-twitter" type="text" name="twitter" placeholder="https://twitter.com/usuario" value="{{ old('twitter', $user->profile->twitter) }}"/>					
		@if($errors->has('twitter'))
			<span class="error">{{ $errors->first('twitter') }}</span>
		@endif
	</div>
</div>

<div class="table-line">
	<div class="table-cell"><label for="create-twitter"><strong>Habilidades:</strong></label></div>
	<div class="table-cell">
		@foreach($skills as $skill)
			<label for="skill_{{ $skill->id }}" class="checkbox-label">
				<input name="skills[{{ $skill->id }}]" 
					type="checkbox" 
					id="skill_{{ $skill->id }}" 
					value="{{ $skill->id }}"
					{{ old("skills.{$skill->id}", $user->skills->contains($skill)) ? 'checked' : '' }}>
				{{ $skill->name }}
			</label>
		@endforeach
		@if($errors->has('skills'))
			<span class="error">{{ $errors->first('skills') }}</span>
		@endif
	</div>
</div>

<div class="table-line">
	<div class="table-cell"><label for="create-twitter"><strong>Rol:</strong></label></div>
	<div class="table-cell">
		@foreach($roles as $role => $name)
			<label for="role_{{ $role }}" class="checkbox-label">
				<input name="role" 
					type="radio" 
					id="role_{{ $role }}" 
					value="{{ $role }}"
					{{ old('role', $user->role) == $role ? 'checked' : '' }}
					>
				{{ $name }}
			</label>
		@endforeach
		@if($errors->has('role'))
			<span class="error">{{ $errors->first('role') }}</span>
		@endif
	</div>
</div>