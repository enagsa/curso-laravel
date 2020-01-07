<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>@yield('title') - CursoLaravel.test</title>
    <link rel="icon" href="favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i&display=swap" rel="stylesheet">
    <link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/reset.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/estilos.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{ asset('/js/jquery.min.js') }}"></script>
</head>
<body>
    <header>   
        <div id="logo">
            <a href="{{ asset('/') }}">Curso de Laravel</a>
        </div>
        <nav id="menu-superior">
            <ul>
                @include('menu')
            </ul>
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
    <footer>
        <div id="copyright">&copy; Enrique Aguilar {{ date('Y') }}</div>
    </footer>
</body>
</html>