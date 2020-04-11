
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" id="tarjeta"><i class="fa fa-reorder"></i>&nbsp;Roles</div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-3">
                            <div class="card">              
                                <div class="card-body">
                                    <h4>Formulario</h4>
                                    <form method="POST" id="form1" name="form1" action="{{ route('guardar_rol') }}" enctype="multipart/form-data">
                                        <input type="text" id="id_arbol" name="id_arbol[]" value="" hidden />
                                        <input type="text" id="id_rol" name="id_rol" value="" hidden />
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
                                                        @foreach ($exitos->all() as $exi)
                                                            {{ $exi }}
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                        {{ csrf_field() }} 
                                        <div class="form-group">
                                          <input type="text" class="form-control" id="nombre_rol" placeholder="Nombre rol" name="nombre_rol" value="{{$nombre_rol}}">
                                        </div>
                                        <div class="form-group">
                                            <label>Permisos</label>     
                                            <div id="container2"></div>
                                        </div>
                                        @if ($accesos['guardar_rol']=='true'  )
                                        <button type="submit" <?php echo ($accesos['guardar_rol']=='true'?'':'hidden'); ?> onclick="obtener()" class="btn btn-success">Guardar</button>
                                        @endif
                                        <a class="btn btn-danger" id="btn_cancelar" name="btn_cancelar" hidden href="#" onclick="cancelar();">Cancelar</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="card-body">
                                <h4>Lista de roles</h4>
                                <table id="buscador_tabla" class="table table-striped table-bordered " >
                                  <thead>
                                    <tr>
                                      <th scope="col">Nro</th>
                                      <th scope="col">Rol</th>
                                      <th hidden scope="col">Accesos Permitidos</th>
                                      <th scope="col">Acción</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php $contador=0; ?>
                                    @foreach($roles as $r)
                                      <?php $contador++; ?>
                                      <tr>
                                        <th scope="row">{{$contador}}</th>
                                        <td>{{$r->nombre_rol}}</td>
                                        <td hidden>{{$r->id_permisos}}</td>
                                        <td>
                                            @if ($accesos['guardar_rol']=='true'  )
                                            <a class="btn btn-primary"  href="#" onclick="editar('{{$r->id_permisos}}','{{$r->nombre_rol}}','{{$r->id_rol}}');" ><i class=" fa fa-pencil-square-o"></i>Editar</a>
                                            @endif
                                            @if ($accesos['eliminar_roles']=='true')
                                            <a class="btn btn-danger"  href="#" onclick="eliminar('{{$r->id_rol}}');"><i class="fa fa-trash"></i>Eliminar</a>
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
        </div>
    </div>
</div>


<script>
    $(function () {
        /*var ajaxResponse = 
            '<ul>' +
            '<li  data-checkstate="unchecked" id="x"   >Raiz' +
            '    <ul>' +
            '        <li data-checkstate="unchecked"  >Rol' +
            '            <ul>' +
            '                <li data-checkstate="unchecked" id="juan"  >Lista</li>' +
            '                <li data-checkstate="unchecked">Nuevo</li>' +
            '                <li data-checkstate="unchecked">Editar</li>' +
            '                <li data-checkstate="unchecked">Eliminar</li>' +
            '            </ul>' +
            '        </li>' +
            '        <li data-checkstate="unchecked">Usuario' +
            '            <ul>' +
            '                <li data-checkstate="unchecked">Lista</li>' +
            '                <li data-checkstate="unchecked">Nuevo</li>' +
            '                <li data-checkstate="unchecked">Editar</li>' +
            '                <li data-checkstate="unchecked">Eliminar</li>' +
            '            </ul>' +
            '        </li>' +
            '        <li data-checkstate="unchecked">Persona' +
            '            <ul>' +
            '                <li data-checkstate="unchecked">Lista</li>' +
            '                <li data-checkstate="unchecked">Nuevo</li>' +
            '                <li data-checkstate="unchecked">Editar</li>' +
            '                <li data-checkstate="unchecked">Eliminar</li>' +
            '            </ul>' +
            '        </li>' +
            '    </ul>' +
            '</li>' +
            '</ul>';*/

        var tree = $("#container2");
        var arbol='<?php echo $tree; ?>';
        tree.html(arbol);

        tree.on('loaded.jstree', function () {

            $('li[data-checkstate="checked"]').each(function () {
                $(this).addClass('jstree-checked');

            });
            $('li[data-checkstate="undetermined"]').each(function () {
                $(this).addClass('jstree-undetermined');
            });
            
        });

        tree.jstree({
            plugins: ["checkbox"],
            core: {
                "themes": {
                    "icons": true
                }
            }
        });
    });
    function obtener(){

        var checked_ids = []; 
        var selectedNodes = $('#container2').jstree("get_selected", true);
        $.each(selectedNodes, function() {
            if(this.id!=0){//No cargamos la raiz
               checked_ids.push(this.id);
            }
        });
        document.getElementById("id_arbol").value =checked_ids;    
    }

    $(document).ready(function() { 
        var instance = $('#container2').jstree(true);
        instance.open_all(); 

        var id_permisos = '<?php echo ($permisos_seleccionados); ?>';
        var instance = $('#container2').jstree(true);
        id_permisos = id_permisos.replace('{', '');
        id_permisos = id_permisos.replace('}', '');
        var permisos = id_permisos.split(",");

        instance.deselect_all();  
        for (var i = 0; i < permisos.length; i++) {
              instance.select_node(permisos[i]);
        }
        instance.open_all(); 

        if(parseInt('<?php echo $id_rol; ?>')==0){
            document.getElementById('btn_cancelar').hidden =true; 
        }
        else{
            document.getElementById('btn_cancelar').hidden =false; 
        }
       
        document.getElementById("id_rol").value = '<?php echo $id_rol; ?>';  //para editar

    });

    function editar(id_permisos,nombre_rol,id_rol){
      var instance = $('#container2').jstree(true);
      id_permisos = id_permisos.replace('{', '');
      id_permisos = id_permisos.replace('}', '');
      var permisos = id_permisos.split(",");

      instance.deselect_all();  
      for (var i = 0; i < permisos.length; i++) {
            instance.select_node(permisos[i]);
      }
      instance.open_all(); 
      document.getElementById('nombre_rol').value =nombre_rol; 
      document.getElementById('btn_cancelar').hidden =false; 
      document.getElementById("id_rol").value =id_rol;  //para editar
      
    }
    function eliminar(id_rol){
        var url="{{ route('eliminar_roles',0) }}";
        url = url.replace("/0", "/"+id_rol);

        var result = confirm("Quire eliminar realmente?");
        if(result){
           document.location.href=url;
        }
    }
    function cancelar(){
      var instance = $('#container2').jstree(true);
      instance.deselect_all(); 
      document.getElementById("nombre_rol").value ='';  
      document.getElementById('btn_cancelar').hidden =true; 
      document.getElementById("id_rol").value =0;  //para editar
    }
</script>
@stop


