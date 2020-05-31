<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
@include('menu.script_header')
</head>
<div class="loader" id="loader"></div>
<body style="background-color: #F9F9FF">
    <div id="app" >
        <nav class="navbar navbar-expand-md shadow-sm bg-gradient"  >
            <div class="container">
                <div class="logo">
                    <img src="../img/logo.png" width="150">
                </div>
                <!-- <h3 class="base">
                    <a class="navbar-brand" href="{{ url('/') }}"> Sistema base </a>
                </h3> -->
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
                    <!-- <ul class="navbar-nav ml-auto">
                        @guest
                            @include('menu.menu_publico')
                        @else
                            @include('menu.menu_sesion')
                        @endguest
                    </ul> -->
                </div>
                <div>
                    @if(Auth::user()->foto)
                       <img src="{{ asset('imagen_usuario/'.Auth::user()->foto) }}" width="10%" class="rounded-circle pull-right" alt="Responsive image" ><br>
                    @else
                       <img src="{{ asset('imagen_carga/'.'avatar2.png') }}" width="10%" class="rounded-circle pull-right" alt="Responsive image" ><br>
                    @endif
                </div>
            </div>
        </nav>
        <!-- Cambios Panel Dashboard -->
        <div class="wrapper">
            <div id="content" class="container mt-4">
                @yield('content')
            </div>
        </div>

        <!-- <main class="py-4" id="content"> -->


        <!-- </main> -->
    </div>
    @include('menu.script_footer')
</body>
</html>
