<!doctype html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
	</head>
	<body>
		<h1>{{ $title }}</h1>

		<hr/>

		<ul>
			@forelse($users as $user)
				<li>{{ $user }}</li>
			@empty
				<li>No hay usuarios registrados</li>
			@endforelse
		</ul>
	</body>
</html>