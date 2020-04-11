@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" id="tarjeta">Bienvenido</div>
                <div class="card-body">
                    
                    <label>{{ Auth::user()->name }}</label><br>
                    @if(Auth::user()->foto)
                       <img src="{{ asset('imagen_usuario/'.Auth::user()->foto) }}" width="20%" class="img-thumbnail" alt="Responsive image" ><br>
                    @else
                       <img src="{{ asset('imagen_carga/'.'avatar2.png') }}" width="20%" class="img-thumbnail" alt="Responsive image" ><br>
                    @endif
                    
                    <label>{{ Auth::user()->email }}</label><br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
