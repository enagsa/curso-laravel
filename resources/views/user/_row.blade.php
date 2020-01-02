<div class="table-line">
	<div class="table-cell">{{ $user->id }}</div>
	<div class="table-cell">
		<h3>{{ $user->name }}</h3>
		<div>Nombre de la Empresa</div>
		<div>{{ $user->profile->profession->title }}</div>
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