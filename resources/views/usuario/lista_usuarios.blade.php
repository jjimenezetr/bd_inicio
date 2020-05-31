@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-12">
          <div class="card "  >
              <div class="card-header" id="tarjeta"><i class="fa fa-reorder"></i>&nbsp;Lista de usuarios </div>
              <div class="card-body " >
                    @if ($accesos['nuevo_usuario']=='true')
                    <a class="btn btn-success" id="btn_nuevo" name="btn_nuevo"  href="{{ route('nuevo_usuario',['id' =>0])}}" ><i class="fa fa-user"></i> &nbsp;Nuevo</a>
                    @endif
                    <table id="buscador_tabla" class="table table-striped table-bordered" >
                      <thead>
                        <tr>
                          <th scope="col">Nro</th>
                          <th scope="col">Usuario</th>
                          <th scope="col">Correo</th>
                          <th scope="col">Persona</th>
                          <th scope="col">Roles</th>
                          <th scope="col">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $contador=0; ?>
                        @foreach($usuarios as $u)
                        <?php $contador++; ?>
                        <tr>
                          <th scope="row">{{$contador}}</th>
                          <td>{{$u->nombre_usuario}}</td>
                          <td>{{$u->correo}}</td>
                          <td><?php echo $u->nombre.' '.$u->apellido_paterno.' '.$u->apellido_materno ; ?></td>
                          <td> <?php echo ($u->roles); ?> </td>
                          <td>
                            @if ($accesos['editar_usuario']=='true')
                            <a class="btn btn-primary" href="{{ route('editar_usuario',['id' => $u->id_usuario])}}" title="Editar" ><i class=" fa fa-pencil-square-o"></i></a>
                            @endif
                            @if ($accesos['eliminar_usuario']=='true')
                            <a class="btn btn-danger" href="#" onclick="eliminar('{{$u->id_usuario}}');" title="Eliminar"><i class="fa fa-trash"></i></a>
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
  function eliminar(id){
    var url="{{route('eliminar_usuario',0)}}";
    url = url.replace("/0", "/"+id);

      var result = confirm("Quire eliminar realmente ?");
      if(result){
          document.location.href=url;
      }
  }
</script>
@stop
