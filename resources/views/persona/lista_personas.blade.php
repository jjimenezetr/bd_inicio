
@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header" id="tarjeta"><i class="fa fa-reorder"></i>&nbsp;Lista de personas</div>
          <div class="card-body">
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
            @if ($accesos['nuevo_persona']=='true')
            <a class="btn btn-success" id="btn_nuevo" name="btn_nuevo" href="{{ route('nuevo_persona',['id' =>0])}}" >Nuevo</a>
            @endif
            <table id="buscador_tabla" class="table table-striped table-bordered " >
              <thead>
                <tr>
                  <th scope="col">Nro</th>
                  <th scope="col">Nombres</th>
                  <th scope="col">Ape. Paterno</th>
                  <th scope="col">Ape. Materno</th>
                  <th scope="col">Ci</th>
                  <th scope="col">Fecha de nacimiento</th>
                  <th scope="col">Celular</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php $contador=0; ?>
                @foreach($personas as $p)
                <?php $contador++; ?>
                <tr>
                  <th scope="row">{{$contador}}</th>
                  <td>{{$p->nombre}}</td>
                  <td>{{$p->apellido_paterno}}</td>
                  <td>{{$p->apellido_materno}}</td>
                  <td>{{$p->ci}}</td>
                  <td>{{$p->fecha_nacimiento}}</td>
                  <td>{{$p->celular}}</td>
                  <td>
                  @if ($accesos['editar_persona']=='true')
                    <a class="btn btn-primary" href="{{ route('editar_persona',['id' => $p->id_persona])}}" ><i class=" fa fa-pencil-square-o"></i>Editar</a>
                  @endif
                  @if ($accesos['eliminar_persona']=='true')
                    <a class="btn btn-danger" href="#" onclick="eliminar('{{$p->id_persona}}');"><i class="fa fa-trash"></i>Eliminar</a>
                  @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
  </div>
</div>
<script type="text/javascript">
  window.setTimeout(function() {
      $(".exito").fadeTo(500, 0).slideUp(500, function(){
          $(this).remove(); 
      });
  }, 2000);
  function eliminar(id){
    var url="{{route('eliminar_persona',0)}}";
    url = url.replace("/0", "/"+id);

      var result = confirm("Quire eliminar realmente ?");
      if(result){
          document.location.href=url;
      }
  }
</script>
@stop

