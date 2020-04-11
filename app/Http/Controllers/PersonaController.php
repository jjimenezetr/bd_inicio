<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Illuminate\Support\MessageBag;
use Auth;

class PersonaController extends Controller{
    
    public function __construct(Request $request )
    {
        $this->verificarAcceso();
    }
    public function lista_personas(){
        $errors = new MessageBag;
        $exitos = new MessageBag;  

    	$personas=DB::select('select p.id_persona,p.nombre,p.apellido_paterno,p.apellido_materno,p.ci,p.celular,p.fecha_nacimiento from tpersona p where p.estado=? order by p.id_persona desc',["activo"]);
    	$arrayParametros=[
    		'personas'=>$personas,
    		'errors'=>$errors,
    		'exitos'=>$exitos,
            'accesos'=>$this->accesos
    	];
    	return view('persona/lista_personas',$arrayParametros);
    }
    public function nuevo_persona($id, Request $request){
        $arrayParametros=[];
        $arrayParametros=$this->formulario_persona($id, $request,$arrayParametros);;
        
        return view('persona/formulario_persona',$arrayParametros);	
    }
    public function editar_persona($id, Request $request){
        $arrayParametros=[];
        $arrayParametros=$this->formulario_persona($id, $request,$arrayParametros);
        
        return view('persona/formulario_persona',$arrayParametros); 
    }
    public function formulario_persona($id,$request,$arrayParametros){
        $errors = new MessageBag;
        $exitos = new MessageBag;  
        
        $method = $request->method();
        if ($request->isMethod('post')) {
            $arrayParametros=$this->guardar_persona($request,$errors,$exitos,$arrayParametros);
        }
        else{
            $arrayParametros=$this->get_parametros_iniciales($id,$errors,$exitos,$request);  
        }

        return $arrayParametros;
    }
    public function get_parametros_iniciales($id,$errors,$exitos,$request){
        if($id==0){
	        $arrayParametros=[
	            'nombre'=>'',
	            'apellido_paterno'=>'',
	            'apellido_materno' =>'',
	            'ci' =>'',
	            'celular'=>'',
	            'fecha_nacimiento'=>'',
	            'id_persona'=>$id,
	            'errors' =>$errors,
	            'exitos' =>$exitos,
                'accesos'=>$this->accesos,
                'formulario_usuario'=>$request->route()->getActionMethod()
	        ];        
	    }else{
	    	$personas=DB::select('select p.id_persona,p.nombre,p.apellido_paterno,p.apellido_materno,p.ci,p.celular,p.fecha_nacimiento 
	    		                  from tpersona p 
	    		                  where p.id_persona=? and p.estado=?',[$id,"activo"]);
	        $arrayParametros=[
	            'nombre'=>$personas[0]->nombre,
	            'apellido_paterno'=>$personas[0]->apellido_paterno,
	            'apellido_materno' =>$personas[0]->apellido_materno,
	            'ci' =>$personas[0]->ci,
	            'celular'=>$personas[0]->celular,
	            'fecha_nacimiento'=>$personas[0]->fecha_nacimiento,
	            'id_persona'=>$id,
	            'errors' =>$errors,
	            'exitos' =>$exitos,
                'accesos'=>$this->accesos,
                'formulario_usuario'=>$request->route()->getActionMethod()
	        ];
        }

        return $arrayParametros;
    }
    public function guardar_persona($request,$errors,$exitos,$arrayParametros){
   
        $validar=$this->validar_persona($request,$errors,$exitos);
        $arrayParametros=$this->get_parametros($request,$validar,$errors,$exitos);
        if($validar==true and $request->id_persona==0){
			DB::insert('insert into tpersona (nombre,apellido_paterno,apellido_materno,ci,celular,fecha_nacimiento,estado) values (?,?,?,?,?,?,?)',[$request->nombre,$request->apellido_paterno,$request->apellido_materno,$request->ci,$request->celular,$request->fecha_nacimiento,"activo"]);
        }
        if($validar==true and $request->id_persona>0){
			DB::insert('update  tpersona set nombre =?, apellido_paterno =?,apellido_materno=?,ci=?,celular=?,fecha_nacimiento=? where id_persona=?;',[$request->nombre,$request->apellido_paterno,$request->apellido_materno,$request->ci,$request->celular,$request->fecha_nacimiento,$request->id_persona]);
        }

        return $arrayParametros;
    }
    public function validar_persona($request,$errors,$exitos){

        $bandera=true;

        //validacion general
        if(trim($request->nombre)==''){
            $errors->add('error',' El campo nombre es requerido');
            $bandera=false;
        }
        if(trim($request->apellido_paterno)==''){
            $errors->add('error',' El campo apellido paterno es equerido');
            $bandera=false;
        }

        if(trim($request->apellido_materno)==''){
            $errors->add('error',' El campo apellido materno es equerido');
            $bandera=false;
        }
        if(trim($request->ci)==''){
            $errors->add('error',' El campo ci es equerido');
            $bandera=false;
        }
        if(trim($request->fecha_nacimiento)==''){
            $errors->add('error',' El campo fecha de nacimiento es equerido');
            $bandera=false;
        }

        //validacion para editar registros
        if($request->id_persona!=0){
            $duplicado_persona=DB::select('select 
                                      count(*)::integer as cantidad  
                                      from tpersona p 
                                      where  trim(upper(p.ci))=trim(upper(?)) and p.id_persona != ? and p.estado=? ',[$request->ci,$request->id_persona,"activo"]);
            if((int)($duplicado_persona[0]->cantidad)>0){
                $errors->add('error',' El ci ya esta registrado'); 
                $bandera=false;
            }
        }
        else{//validacion para registros nuevos
            $duplicado_persona=DB::select('select 
                                      count(*)::integer as cantidad  
                                      from tpersona p 
                                      where  trim(upper(p.ci))=trim(upper(?)) and p.estado=? ',[$request->ci,"activo"]);
            if((int)($duplicado_persona[0]->cantidad)>0){
                $errors->add('error',' El ci ya esta registrado'); 
                $bandera=false;
            }
        }

        // mensaje para mostrar en caso de exito
        if($bandera==true and $request->id_persona!=0){
           $exitos->add('exit','Exito!! Cambios realizados correctamente');
        }
        if($bandera==true and $request->id_persona==0){
           $exitos->add('exit','Exito!! Registrado correctamente');
        }

        return $bandera;
    }
    public function get_parametros($request,$validar,$errors,$exitos){
	
        if($validar==true && $request->id_persona==0){
	        $arrayParametros=[
	            'nombre'=>'',
	            'apellido_paterno'=>'',
	            'apellido_materno' =>'',
	            'ci' =>'',
	            'celular'=>'',
	            'id_persona'=>$request->id_persona,
	            'fecha_nacimiento'=>'',
	            'errors' =>$errors,
	            'exitos' =>$exitos,
                'accesos'=>$this->accesos,
                'formulario_usuario'=>$request->route()->getActionMethod()
	        ];        
	    }else{

	        $arrayParametros=[
	            'nombre'=>$request->nombre,
	            'apellido_paterno'=>$request->apellido_paterno,
	            'apellido_materno' =>$request->apellido_materno,
	            'ci' =>$request->ci,
	            'celular'=>$request->celular,
	            'fecha_nacimiento'=>$request->fecha_nacimiento,
	            'id_persona'=>$request->id_persona,
	            'errors' =>$errors,
	            'exitos' =>$exitos,
                'accesos'=>$this->accesos,
                'formulario_usuario'=>$request->route()->getActionMethod()
	        ];
        }

        return $arrayParametros;
    }
    public function eliminar_persona($id){
        $errors = new MessageBag;
        $exitos = new MessageBag; 
        $bandera=true;
        

    	$personas=DB::select('select p.id_persona,p.nombre,p.apellido_paterno,p.apellido_materno,p.ci,p.celular,p.fecha_nacimiento from tpersona p where p.estado =? order by p.id_persona desc ',["activo"]);


        $existe_usuario=DB::select('select count(*)::integer as cantidad from users u where u.id_persona=? and u.estado=? ',[$id,"activo"]);
        //dd($existe_usuario[0]->cantidad);
        if((int)($existe_usuario[0]->cantidad)>0){
            $errors->add('error',' La persona esta asociado a '.($existe_usuario[0]->cantidad).' usuario(s)'); 
            $bandera=false;
        }else{
            $exitos->add('exito','Exito!! Eliminado correctamente');
            DB::update('update tpersona set estado=? where id_persona=?',["inactivo",$id]);
            return redirect()->route('lista_personas');
        }

        $arrayParametros=[
            'personas'=>$personas,
            'errors'=>$errors,
            'exitos'=>$exitos,
            'accesos'=>$this->accesos,
        ];

    	return view('persona/lista_personas',$arrayParametros);
    }
}
