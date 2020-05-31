@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" id="tarjeta">Bienvenido</div>
                <div class="card-body row">
                    <div class="col-12 col-md-6 ">
                        @if(Auth::user()->foto)
                        <img src="{{ asset('imagen_usuario/'.Auth::user()->foto) }}" width="100%" class="img-thumbnail rounded-circle" alt="Responsive image" ><br>
                        @else
                        <img src="{{ asset('imagen_carga/'.'avatar2.png') }}" width="100%" class="img-thumbnail rounded-circle" alt="Responsive image" ><br>
                        @endif
                    </div>
                    <div class="col-12 col-md-6">
                        <h2 style="color:#259dfe">{{ Auth::user()->name }}</h2>
                        <hr>
                        <h4 >{{ Auth::user()->email }}</h4>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
