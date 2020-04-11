<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Illuminate\Support\MessageBag;
use Auth;

class RolController extends Controller
{
    public function __construct(Request $request )
    {
        $this->verificarAcceso();
    }
    public function lista_roles(Request $request){

    	$permisos =DB::select('select p.id_permiso,
							p.nombre_acceso,
							p.ruta
							from tpermiso p');

    	$roles=DB::select('select r.id_rol,
    		                r.nombre_rol ,
                            r.id_permisos
    		                from trol r where r.estado=? order by r.id_rol desc ',["activo"]);

        $res=$this->rolesRecursivo();
    	$errors = new MessageBag;
        $exitos = new MessageBag;
        $arrayParametros=[
            //'permisos'=>$permisos, 
            'roles'=>$roles,
            'errors' =>$errors,
            'exitos' =>$exitos,
            'tree'=>$res,
            'nombre_rol'=>'',
            'permisos_seleccionados'=>'',
            'id_rol'=>0,
            'accesos'=>$this->accesos,
            'formulario_usuario'=>$request->route()->getActionMethod()
        ];
        
    	return view('rol/lista_roles',$arrayParametros);
    }
    public function rolesRecursivo(){
        $contador=0;
        $arbol='<ul>';
        $arbol.='<li  data-checkstate="unchecked" id="0" >Raiz';
        $arbol.='<ul>';
        foreach ($this->consulta_permisos(null) as $p ) {
            $arbol.='<li data-checkstate="unchecked"  id="'.$p->id_permiso.'" >'.$p->nombre_acceso;
            $contador=0;
            foreach ($this->consulta_permisos($p->id_permiso) as $p2 ) {
                $contador++;
                if($contador==1){
                   $arbol.='<ul>';
                }
                $arbol.='<li data-checkstate="unchecked" id="'.$p2->id_permiso.'" > '.$p2->nombre_acceso;
                $arbol.='</li>';
            }
            if($contador>0){
               $arbol.='</ul>';
            }
            $arbol.='</li>'; 
        }
        $arbol.='</ul>';
        $arbol.='</li>';
        $arbol.='</ul>';
        return $arbol;
    }
    public function consulta_permisos($id_padre){

        if($id_padre==null){
                $tree=DB::select('WITH RECURSIVE tree AS(
                                                        select
                                                        p.id_padre,p.id_permiso,p.nivel,p.orden_logico,p.nombre_acceso, p.codigo, (p.id_permiso)::text as orden
                                                        from tpermiso p
                                                        where p.id_padre is null 
                                                        union all
                                                        select 
                                                        p.id_padre,p.id_permiso,p.nivel,p.orden_logico,p.nombre_acceso, p.codigo ,(t.orden||?||p.id_permiso)::text as orden
                                                        from tpermiso p
                                                        join tree t on t.id_permiso=p.id_padre
                                                        )
                                                        select 
                                                        t.id_permiso,
                                                        t.id_padre,
                                                        t.nombre_acceso,
                                                        t.orden_logico,
                                                        t.codigo,
                                                        t.nivel,
                                                        t.orden,
                                                        (select count(p.id_padre) from tpermiso p where p.id_padre=t.id_permiso) as cant_hijos
                                                        from tree t
                                                        where t.id_padre is null
                                                        order by 
                                                        t.orden_logico asc;',['->']);
        }
        else{
                $tree=DB::select('WITH RECURSIVE tree AS(
                                                        select
                                                        p.id_padre,p.id_permiso,p.nivel,p.orden_logico,p.nombre_acceso, p.codigo, (p.id_permiso)::text as orden
                                                        from tpermiso p
                                                        where p.id_padre is null 
                                                        union all
                                                        select 
                                                        p.id_padre,p.id_permiso,p.nivel,p.orden_logico,p.nombre_acceso, p.codigo ,(t.orden||?||p.id_permiso)::text as orden
                                                        from tpermiso p
                                                        join tree t on t.id_permiso=p.id_padre
                                                        )
                                                        select 
                                                        t.id_permiso,
                                                        t.id_padre,
                                                        t.nombre_acceso,
                                                        t.orden_logico,
                                                        t.codigo,
                                                        t.nivel,
                                                        t.orden,
                                                        (select count(p.id_padre) from tpermiso p where p.id_padre=t.id_permiso) as cant_hijos
                                                        from tree t
                                                        where t.id_padre = ?
                                                        order by 
                                                        t.orden_logico asc;',['->',$id_padre]);

        }
          
        return $tree;
    }
    public function guardar_rol(Request $request){
        
        $errors = new MessageBag;
        $exitos = new MessageBag;    
        $arrayParametros=[];    
        $validar=$this->validar_rol($request,$errors,$exitos);
        if($validar==true){ 
            if($request->id_rol==0){
                DB::insert('insert into trol (nombre_rol,id_permisos,estado) values(?, ?,?);',[$request->nombre_rol,'{'.$request->id_arbol[0].'}',"activo" ]);
            }   
            else{
                DB::update('update trol set nombre_rol =?,id_permisos=? where id_rol=?;',[$request->nombre_rol,'{'.$request->id_arbol[0].'}',$request->id_rol]);
            }   
        }
        $res=$this->rolesRecursivo();
        $arrayParametros=$this->getParametros($request,$errors,$exitos,$res,$validar);


        //return redirect('lista_roles'); 
        return view('rol/lista_roles',$arrayParametros);
    }
    public function validar_rol($request,$errors,$exitos){
        $bandera=true;
        if(trim($request->nombre_rol)==''){
            $errors->add('nombre_rol',' El nombre rol es requerido');
            $bandera=false;
        }
        if($request->id_arbol[0]==null){
            $errors->add('id_arbol',' Permisos no seleccionados');
            $bandera=false;
        }
        if($request->id_rol!=0){
            $dubicado_rol=DB::select('select 
                                      count(*)::integer as cantidad  
                                      from trol r 
                                      where  trim(upper(r.nombre_rol))=trim(upper(?)) and r.id_rol != ? and r.estado=? ',[$request->nombre_rol,$request->id_rol,"activo"]);
            if((int)($dubicado_rol[0]->cantidad)>0){
                $errors->add('error',' El nombre rol ya esta registrado'); 
                $bandera=false;
            }
           
        }
        else{
            $dubicado_rol=DB::select('select 
                                      count(*)::integer as cantidad  
                                      from trol r
                                      where  trim(upper(r.nombre_rol))=trim(upper(?)) and r.estado=? ',[$request->nombre_rol,"activo"]);
            if((int)($dubicado_rol[0]->cantidad)>0){
                $errors->add('error',' El nombre rol ya esta registrado'); 
                $bandera=false;
            }
        }


        if($bandera==true and $request->id_rol!=0){
           $exitos->add('exi','Exito!! Cambios realizados correctamente ');
           //$exitos->add('exi','Actualizar el navegador para sufrir efectos ');
        }
        if($bandera==true and $request->id_rol==0){
           $exitos->add('exi','Exito!! Registrado correctamente');
           //$exitos->add('exi','Actualizar el navegador para sufrir efectos ');
        }

        return $bandera;
    }
    public function eliminar_roles($id){
        db::update('update  trol set estado=? where id_rol=? ',["inactivo",$id]);
        return redirect()->route('lista_roles');
    }
    public function getParametros($request,$errors,$exitos,$res,$validar){

        if($validar==true){
            $arrayParametros=[
                //'permisos'=>DB::select('select p.id_permiso,p.nombre_acceso,p.ruta from tpermiso p'),
                'roles'=>DB::select('select r.id_rol, r.nombre_rol,r.id_permisos  from trol r where r.estado=? order by r.id_rol desc ',["activo"]),
                'errors' =>$errors,
                'exitos' =>$exitos,
                'tree'=>$res,
                'nombre_rol'=>'',
                'permisos_seleccionados'=>'',
                'id_rol'=>0,
                'accesos'=>$this->accesos,
            ];
        }
        else{
            $arrayParametros=[
                //'permisos'=>DB::select('select p.id_permiso,p.nombre_acceso,p.ruta from tpermiso p'),
                'roles'=>DB::select('select r.id_rol, r.nombre_rol,r.id_permisos  from trol r where r.estado=? order by r.id_rol desc ',["activo"]),
                'errors' =>$errors,
                'exitos' =>$exitos,
                'tree'=>$res,
                'nombre_rol'=>$request->nombre_rol,
                'permisos_seleccionados'=>($request->id_arbol[0]==null?null:'{'.$request->id_arbol[0].'}'),
                'id_rol'=> $request->id_rol,
                'accesos'=>$this->accesos,
            ];
        }


        return $arrayParametros;
    }
}

