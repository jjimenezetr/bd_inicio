<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
@include('menu.script_header')
</head>
<div class="loader" id="loader"></div>
<body style="background-color: #F9F9FF">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light  shadow-sm"  >
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}"> Sistema base </a>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li> <a class="dropdown-item" href="{{ url('/home') }}">Inicio</a></li> 
                        @if ($accesos['lista_usuarios']=='true')
                        <li> <a class="dropdown-item" href="{{route('lista_usuarios')}}">Usuarios</a></li>
                        @endif
                        @if ($accesos['lista_roles']=='true')
                        <li> <a class="dropdown-item" href="{{route('lista_roles')}}">Roles</a></li> 
                        @endif
                        @if ($accesos['lista_personas']=='true')
                        <li> <a class="dropdown-item" href="{{route('lista_personas')}}">Personas</a></li> 
                        @endif
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        @guest
                            @include('menu.menu_publico')
                        @else
                            @include('menu.menu_sesion')
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <br>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @include('menu.script_footer')
</body>
</html>
