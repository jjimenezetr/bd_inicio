
@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-12">
          <div class="card">
            <div class="card-header" id="tarjeta">Formulario de personas</div>
              <div class="card-body">
                <form method="POST" id="form1" name="form1" action="{{ route($formulario_usuario,$id_persona) }}" enctype="multipart/form-data">
                    {{ csrf_field() }} 
                    <input type="text" id="id_persona" name="id_persona" value="{{$id_persona}}" hidden />
                    <div class="flash-message">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($exitos->any())
                            <div class="alert alert-success exito" role="alert">
                                <ul>
                                    @foreach ($exitos->all() as $ex)
                                        {{ $ex }}
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                      <label for="label">Nombres</label>
                      <input type="text" class="form-control" id="nombre" maxlength="50" name="nombre" value="{{$nombre}}">
                    </div>
                    <div class="form-group">
                      <label for="label">Apellido Paterno</label>
                      <input type="text" class="form-control" id="apellido_paterno" maxlength="50"  name="apellido_paterno" value="{{$apellido_paterno}}">
                    </div>
                    <div class="form-group">
                      <label for="label">Apellido Materno</label>
                      <input type="text" class="form-control" id="apellido_materno" maxlength="50"  name="apellido_materno" value="{{$apellido_materno}}">
                    </div>
                    <div class="form-group">
                      <label for="label">Ci</label>
                      <input type="text" class="form-control" id="ci" maxlength="20"  name="ci" value="{{$ci}}">
                    </div>
                    <div class="form-group">
                      <label for="label">Fecha de nacimiento</label>
                      <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control"  pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="{{$fecha_nacimiento}}">
                    </div>
                    <div class="form-group">
                      <label for="label">Celular</label>
                      <input type="text" class="form-control" id="celular" maxlength="20"  name="celular" value="{{$celular}}">
                    </div>
                    <button type="submit" class="btn btn-success">Guardar</button>
                    @if ($accesos['lista_personas']=='true')
                       <a class="btn btn-danger" id="btn_cancelar" name="btn_cancelar"  href="#" onclick="cancelar();">Volver</a>
                    @endif
                </form>
              </div>               
          </div>
      </div>
  </div>
</div>
<script type="text/javascript">
  function cancelar(){
      var url="{{ route('lista_personas') }}";
      document.location.href=url;
  }
</script>
@stop

