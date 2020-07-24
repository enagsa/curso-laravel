<form id="filters" method="GET" action="{{ route('users') }}">
	{{--<div id="toggle-users">
		<label for="users-all"><input type="radio" id="users-all" name="users" value="all" @if(old('users') == 'all') checked='checked' @endif/> Todos</label>
		<label for="users-active"><input type="radio" id="users-active" name="users" value="active" @if(old('users') == 'active') checked='checked' @endif/> Solo activos</label>
		<label for="users-innactive"><input type="radio" id="users-innactive" name="users" value="innactive" @if(old('users') == 'innactive') checked='checked' @endif/> Solo inactivos</label>
	</div>--}}
	<div id="toggle-users">
		@foreach(['' => 'Todos', 'with-team' => 'Con equipo', 'without-team' => 'Sin equipo'] as $value => $text)
			<label for="team-{{ $value ?: 'all' }}"><input type="radio" id="team-{{ $value ?: 'all' }}" name="team" value="{{ $value }}" {{ request('team','') === $value ? 'checked' : '' }}/> {{ $text }}</label>
		@endforeach
	</div>
	<div id="filters-left">
		<div id="search-box">
			<input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}">
			<button id="search-button"><i class="fa fa-search"></i></button>
		</div>
		{{-- <select name="role" id="role-select">
			<option value="">Roles</option>
			@foreach($roles as $role => $name)
				<option value="{{ $role }}" @if(request('role') == $role) selected="selected" @endif>{{ $name }}</option>
			@endforeach
		</select>
		<select name="skill" id="skill-select">
			<option value="">Habilidades</option>
			@foreach($skills as $skill)
				<option value="{{ $skill->id }}" @if(request('skill') == $skill->id) selected="selected" @endif>{{ $skill->name }}</option>
			@endforeach
		</select>
		<div style="clear:both"></div>--}}
	</div>
	{{--<div id="filters-right">
		<label for="date-start">Desde:</label>
		<input type="date" name="date-start" id="date-start" value="{{ request('date-start') }}" placeholder="Desde">
		<label for="date-end">Hasta:</label>
		<input type="date" name="date-end" id="date-end" value="{{ request('date-end') }}" placeholder="Hasta">
		<input type="submit" id="filter" value="Filtrar"/>
	</div>--}}
	<div style="clear:both"></div>
</form>

<script type="text/javascript">
	$(document).ready(function(){
		$('#filters').submit(function () {
	        var $empty_fields = $(this).find(':input').filter(function () { 
	            return $(this).val() === '';
	        });
	        $empty_fields.prop('disabled', true);
	        return true;
	    });
	})
</script>