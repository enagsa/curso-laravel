<form method="POST" action="{{ $action }}">
	{{ $customs }}
	<div class="table user-details">
		{{ $fields }}
	</div>

	<button type="submit" class="volver">{{ $button }}</button>
	<a href="{{ route('users') }}" class="volver">Volver al Listado <i class="fa fa-undo"></i></a>
</form>