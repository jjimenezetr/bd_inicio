@extends('layouts.app')
@section('content')
<style type="text/css">
	.select2-container .select2-selection--single{
	    height:34px !important;
	}
	.select2-container--default .select2-selection--single{
	         border: 1px solid #ccc !important; 
	     border-radius: 0px !important; 
	}
</style>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header" id="tarjeta">Fomulario de usuario</div>
        <div class="card-body">
          <form method="POST" id="form1" name="form1" action="{{ route($formulario_usuario,$id_usuario) }}" accept-charset="UTF-8" enctype="multipart/form-data">
              <input type="text" id="id_usuario" name="id_usuario" value="{{$id_usuario}}" hidden />
              {{ csrf_field() }} 
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
                <label>Persona</label>
                <select class="form-control select2" id="id_persona" name="id_persona">
                <option id="id_persona" name="id_persona" value="0">Seleccionar persona</option>
                    @foreach($personas as $p)
                      @if($id_persona==$p->id_persona)
                          <option value="{{$p->id_persona}}" selected><?php echo ($p->nombre.' '.$p->apellido_paterno.' '.$p->apellido_materno); ?></option> 
                      @else
                          <option value="{{$p->id_persona}}" ><?php echo ($p->nombre.' '.$p->apellido_paterno.' '.$p->apellido_materno); ?></option> 
                      @endif
                    @endforeach
                </select>
              </div>  
              <div class="form-group">
                <label for="label">Usuario</label>
                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="{{$nombre_usuario}}" placeholder="Usuario">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" aria-describedby="emailHelp" value="{{$correo}}" placeholder="Correo">
              </div>
              <div class="form-group">
                <label for="inputPassword">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" value="{{$contrasena}}" placeholder="Contraseña nueva">
              </div>
              <div class="form-group">
                <label for="inputPassword">Repetir contraseña</label>
                <input type="password" class="form-control" id="contrasena2" name="contrasena2" value="{{$contrasena2}}" placeholder="Repetir contraseña">
              </div>
              <div class="form-group">        
                <div>
                  @if($foto)
                  <img onclick="subirFoto()" src="{{ asset('imagen_usuario/'.$foto) }}" id="imagen" name="imagen"  class="img-thumbnail" alt="Foto" style="width: 60px; height: 60px; " >
                  @else
                  <img onclick="subirFoto()" src="{{ asset('imagen_carga/'.'avatar2.png') }}" id="imagen" name="imagen" alt=" Subir Foto" class="img-thumbnail"  style="width: 60px; height: 60px; ">
                  @endif
                </div>
                <input value="{{'imagen_usuario/'.$foto}}" id="foto" class="foto"   name="foto" type="file" hidden />
                <input type="text" class="form-control" id="foto2" name="foto2" value="{{$foto}}" hidden>
              </div>
              <div class="form-group">
                <label for="label">Roles</label>
                <select id="id_roles" name="id_roles[]" class=" form-control select2"  value=""   multiple>
                  @foreach($roles as $r)
                    @if($r->cantidad_roles>0)
                      <option value="{{$r->id_rol}}" selected>{{$r->nombre_rol}}</option> 
                    @else
                      <option value="{{$r->id_rol}}" >{{$r->nombre_rol}}</option> 
                    @endif
                  @endforeach
                </select>
              </div>
              <button type="submit" class="btn btn-success">Guardar</button>
              @if ($accesos['lista_usuarios']=='true')
                  <a class="btn btn-danger" id="btn_volver" name="btn_volver"  href="{{route('lista_usuarios')}}" >Volver</a>
              @endif
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $('.select2').select2();
  function subirFoto(){
     document.getElementById("foto").click();
  }

  function init() {
    var inputFile = document.getElementById('foto');
    inputFile.addEventListener('change', mostrarImagen, false);
  }
  //inicio subir foto
  function mostrarImagen(event) {
    var file = event.target.files[0];
    var reader = new FileReader();
    reader.onload = function(event) {
      var img = document.getElementById('imagen');
      img.src= event.target.result;
    }
    reader.readAsDataURL(file);
  }
  window.addEventListener('load', init, false);
  //fin subir foto
</script>
@stop
