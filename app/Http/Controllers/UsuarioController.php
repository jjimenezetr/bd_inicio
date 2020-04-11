<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function __construct(Request $request )
    {
        $this->verificarAcceso();
    }
    public function lista_usuarios(){
        $errors = new MessageBag;
        $exitos = new MessageBag;

        $usuarios=DB::select('select   u.name as nombre_usuario,
                          u.estado,
                          p.ci,
                          p.nombre,
                          p.apellido_paterno,
                          p.apellido_materno,

                          (select STRING_AGG(r.nombre_rol, ? )  
                          from tusuario_rol ur 
                          join trol r on r.id_rol=ur.id_rol
                          where ur.id_usuario=u.id)::varchar as roles,

                          (select STRING_AGG(r.id_rol::varchar, ? )  
                          from tusuario_rol ur 
                          join trol r on r.id_rol=ur.id_rol
                          where ur.id_usuario=u.id)::varchar as id_roles,
                          u.id as id_usuario,
                          u.password as contrasena,
                          u.email as correo

                          from users u 
                          join tpersona p on p.id_persona=u.id_persona
                          where u.estado=? order by u.id desc ',["<br>","<br>","activo"]);

        $personas=DB::select('select p.id_persona,p.nombre,p.apellido_paterno,p.apellido_materno,p.ci,p.celular,p.fecha_nacimiento from tpersona p where p.estado=?',["activo"]);

    	$personas=DB::select('select p.id_persona,p.nombre,p.apellido_paterno,p.apellido_materno,p.ci,p.celular,p.fecha_nacimiento from tpersona p where p.estado=?',["activo"]);

    	$arrayParametros=[
    		'usuarios'=>$usuarios,
    		'personas'=>$personas,
    		'errors' =>$errors,
            'exitos' =>$exitos,
            'accesos'=>$this->accesos
    	];

    	return view('usuario/lista_usuarios',$arrayParametros);
    }
    public function nuevo_usuario($id, Request $request){

        $arrayParametros=[];
        $arrayParametros=$this->formulario_usuario($id,$request,$arrayParametros);

    	return view('usuario/formulario_usuario',$arrayParametros);
    }
    public function editar_usuario($id, Request $request){
        $arrayParametros=[];
        $arrayParametros=$this->formulario_usuario($id,$request,$arrayParametros);

        return view('usuario/formulario_usuario',$arrayParametros);
    }
    public function formulario_usuario($id,$request,$arrayParametros){
        $errors = new MessageBag;
        $exitos = new MessageBag;  
        
        $method = $request->method();
        if ($request->isMethod('post')) {
            $arrayParametros=$this->guardar_usuario($request,$errors,$exitos,$arrayParametros);
        }
        else{
            $arrayParametros=$this->get_parametros_iniciales($id,$errors,$exitos,$request);  
        }

        return $arrayParametros;
    }
    public function guardar_usuario($request,$errors,$exitos,$arrayParametros){
  
        $validar=$this->validar_usuario($request,$errors,$exitos);

        if($validar==true and $request->id_usuario==0){
			DB::insert('insert into users (name,password,estado,id_persona,email,created_at,foto) values (?,?,?,?,?,now(),?)',[$request->nombre_usuario,Hash::make($request->contrasena),"activo",$request->id_persona,$request->correo,$this->cargarFoto($request)]);
			$id_usuario=DB::select('select max(u.id)::integer as id_usuario from users u');
			
			$this->guardar_usuario_rol($request->id_roles,$id_usuario[0]->id_usuario);
        }
        if($validar==true and $request->id_usuario>0){
            $this->editar($request);
			$this->guardar_usuario_rol($request->id_roles,$request->id_usuario);
        }
        $arrayParametros=$this->get_parametros($request,$validar,$errors,$exitos);

        return $arrayParametros;
    }
    public function cargarFoto($request){

        $foto=$request->foto2;
        if($request->file('foto')){
            $file = $request->file('foto');
            $foto =  $file->getClientOriginalName();
            $file->move(public_path("imagen_usuario"),$foto);  
        }
        return $foto; 
    }
    public function editar($request){
        $foto='';
        $foto=$this->cargarFoto($request);

        if(trim($request->contrasena)==''){
            DB::update('update  users  set name =?,
                                id_persona=?,email=?,updated_at=now(),foto=? where id=?',[$request->nombre_usuario,$request->id_persona,$request->correo,$foto,$request->id_usuario]);
        }
        else{
            DB::update('update  users  set name =?, password=?,
                                id_persona=?,email=?,updated_at=now(),foto=? where id=?',[$request->nombre_usuario,Hash::make($request->contrasena),$request->id_persona,$request->correo,$foto,$request->id_usuario]);
        }
    }
    public function guardar_usuario_rol($roles,$id_usuario){

    	DB::delete('delete from tusuario_rol where id_usuario=?',[$id_usuario]);	
    	for ($i = 0; $i < count($roles); $i++) {
    	    DB::insert('insert into tusuario_rol (id_rol,id_usuario) values(?,?)',[$roles[$i],$id_usuario]);
    	}
    }
    public function validar_usuario($request,$errors,$exitos){

        $bandera=true;

        //validacion general
        if(trim($request->id_persona)==''){
            $errors->add('error',' El campo persona es requerido');
            $bandera=false;
        }
        if(trim($request->nombre_usuario)==''){
            $errors->add('error',' El campo usuario es requerido');
            $bandera=false;
        }
        if($request->id_usuario==0){
            if(trim($request->contrasena)==''){
                $errors->add('error',' El campo contrasena es equerido');
                $bandera=false;
            }
            if(trim($request->contrasena2)==''){
                $errors->add('error',' El campo repetir contrasena es equerido');
                $bandera=false;
            }
            if(trim($request->contrasena)!=trim($request->contrasena2)){
                $errors->add('error',' El campo contrasena no coincide');
                $bandera=false;
            }
        }else{
            if(trim($request->contrasena)!=trim($request->contrasena2)){
                $errors->add('error',' El campo contrasena no coincide');
                $bandera=false;
            }
        }

        //dd($request->id_roles);
        if(!($request->id_roles)){
            $errors->add('error',' El campo rol es requerido');
            $bandera=false;
        }

        //validacion para editar registros
        if($request->id_usuario!=0){
            $duplicado_usuario=DB::select('select 
                                      count(*)::integer as cantidad  
                                      from users u 
                                      where  trim(upper(u.name))=trim(upper(?)) and u.id != ? ',[$request->nombre_usuario,$request->id_usuario]);
            if((int)($duplicado_usuario[0]->cantidad)>0){
                $errors->add('error',' El usuario ingresado ya no esta permitido'); 
                $bandera=false;
            }
            $duplicado_usuario_correo=DB::select('select 
                                      count(*)::integer as cantidad  
                                      from users u 
                                      where  trim(upper(u.email))=trim(upper(?)) and u.id != ? ',[$request->correo,$request->id_usuario]);
            if((int)($duplicado_usuario_correo[0]->cantidad)>0){
                $errors->add('error',' El correo ingresado ya no esta permitido'); 
                $bandera=false;
            }
        }
        else{//validacion para registros nuevos
            $duplicado_usuario=DB::select('select 
                                      count(*)::integer as cantidad  
                                      from users u 
                                      where  trim(upper(u.name))=trim(upper(?)) ',[$request->nombre_usuario]);
            if((int)($duplicado_usuario[0]->cantidad)>0){
                $errors->add('error',' El usuario ingresado ya no esta permitido'); 
                $bandera=false;
            }
            $duplicado_usuario_correo=DB::select('select 
                                      count(*)::integer as cantidad  
                                      from users u 
                                      where  trim(upper(u.email))=trim(upper(?)) ',[$request->correo]);
            if((int)($duplicado_usuario_correo[0]->cantidad)>0){
                $errors->add('error',' El correo ingresado ya no esta permitido'); 
                $bandera=false;
            }
        }
        // mensaje para mostrar en caso de exito
        if($bandera==true and $request->id_usuario!=0){
           $exitos->add('exit','Exito!! Cambios realizados correctamente');
        }
        if($bandera==true and $request->id_usuario==0){
           $exitos->add('exit','Exito!! Registrado correctamente');
        }

        return $bandera;
    }
    public function get_parametros($request,$validar,$errors,$exitos){

        if($validar==true && (int)$request->id_usuario==0){
            $personas=DB::select('select p.id_persona,p.nombre,p.apellido_paterno,p.apellido_materno,p.ci,p.celular,p.fecha_nacimiento from tpersona p where p.estado=?',["activo"]);
            $roles=DB::select('select r.id_rol,r.nombre_rol,0::integer as cantidad_roles from trol r ');
	        $arrayParametros=[
	            
	            'nombre_usuario'=>'',
	            'contrasena'=>'',
	            'contrasena2'=>'',
	            'estado' =>'',
	            'id_persona' =>'',
	            'id_roles'=>[],
	            'roles'=>$roles,
	            'id_usuario'=>$request->id_usuario,
                'correo'=>'',
	            'personas'=>$personas,
	            'errors' =>$errors,
	            'exitos' =>$exitos,
                'accesos'=>$this->accesos,
                'formulario_usuario'=>$request->route()->getActionMethod(),
                'foto'=> ''
	        ];        
	    }else{

            $roles=DB::select('select 
            	                r.id_rol,
            	                r.nombre_rol,
            	                count(ur.id_rol)::integer as cantidad_roles 
								from trol r
								left join tusuario_rol ur on ur.id_rol=r.id_rol and ur.id_usuario=?
								group by r.id_rol,r.nombre_rol ',[$request->id_usuario]);

	    	$personas=DB::select('select p.id_persona,p.nombre,p.apellido_paterno,p.apellido_materno,p.ci,p.celular,p.fecha_nacimiento 
	                  from tpersona p 
	                  where  p.estado=?',["activo"]);

            $usuarios=DB::select('select u.foto  from users u where u.id=? ',[$request->id_usuario]);
            
            if(!$usuarios){ //validacion de usuario en caso que no tenga datos en usuarios
                $usuarios='';
            }
            else{
                $usuarios=$usuarios[0]->foto;
            }

	        $arrayParametros=[
	            'nombre_usuario'=>$request->nombre_usuario,
	            'contrasena'=>$request->contrasena,
	            'contrasena2'=>$request->contrasena2,
	            'estado' =>'',
	            'id_persona' =>$request->id_persona,
	            'id_roles'=>$request->id_roles,
	            'roles'=>$roles,
	            'id_usuario'=>$request->id_usuario,
                'correo'=>$request->correo,
	            'personas'=>$personas,
	            'errors' =>$errors,
	            'exitos' =>$exitos,
                'accesos'=>$this->accesos,
                'formulario_usuario'=>$request->route()->getActionMethod(),
                'foto'=>$usuarios
	        ];
        }

        return $arrayParametros;
    }
    public function get_parametros_iniciales($id,$errors,$exitos,$request){
    	
    	$personas=DB::select('select p.id_persona,p.nombre,p.apellido_paterno,p.apellido_materno,p.ci,p.celular,p.fecha_nacimiento from tpersona p where p.estado=?',["activo"]);

        if($id==0){
        	$roles=DB::select('select r.id_rol,r.nombre_rol,0::integer as cantidad_roles from trol r ');
	        $arrayParametros=[
	            'nombre_usuario'=>'',
	            'contrasena'=>'',
	            'contrasena2'=>'',
	            'estado' =>'',
	            'id_persona' =>'',
	            'id_roles'=>'',
	            'roles'=>$roles,
	            'id_usuario'=>$id,
                'correo'=>'',
	            'personas'=>$personas,
	            'errors' =>$errors,
	            'exitos' =>$exitos,
                'accesos'=>$this->accesos,
                'formulario_usuario'=>$request->route()->getActionMethod(),
                'foto'=> ''
	        ];        
	    }else{
	    	$usuarios=DB::select('select   u.name as nombre_usuario,
								u.estado,
								p.ci,
								p.nombre,
								p.apellido_paterno,
								p.apellido_materno,

								(select STRING_AGG(r.nombre_rol, ? )  
								from tusuario_rol ur 
								join trol r on r.id_rol=ur.id_rol
								where ur.id_usuario=u.id)::varchar as roles,

								(select STRING_AGG(r.id_rol::varchar, ? )  
								from tusuario_rol ur 
								join trol r on r.id_rol=ur.id_rol
								where ur.id_usuario=u.id)::varchar as id_roles,
								u.id as id_usuario,
								u.id_persona,
								u.password as contrasena,
                                u.email as correo,
                                u.foto

								from users u 
								join tpersona p on p.id_persona=u.id_persona
								where u.estado=? and u.id=? order by u.id desc ',[",",",","activo",$id]);

	    	$personas=DB::select('select p.id_persona,p.nombre,p.apellido_paterno,p.apellido_materno,p.ci,p.celular,p.fecha_nacimiento 
	    		                  from tpersona p 
	    		                  where  p.estado=?',["activo"]);

            $roles=DB::select('select 
            	                r.id_rol,
            	                r.nombre_rol,
            	                count(ur.id_rol)::integer as cantidad_roles 
								from trol r
								left join tusuario_rol ur on ur.id_rol=r.id_rol and ur.id_usuario=?
								group by r.id_rol,r.nombre_rol ',[$id]);
	    	
	        $arrayParametros=[
	            'nombre_usuario'=>$usuarios[0]->nombre_usuario,
	            'contrasena'=>'',
	            'contrasena2'=>'',
	            'estado' =>$usuarios[0]->estado,
	            'id_persona' =>$usuarios[0]->id_persona,
	            'id_roles'=>$usuarios[0]->id_roles,
	            'roles'=>$roles,
	            'id_usuario'=>$id,
                'correo'=>$usuarios[0]->correo,
	            'personas'=>$personas,
	            'errors' =>$errors,
	            'exitos' =>$exitos,
                'accesos'=>$this->accesos,
                'formulario_usuario'=>$request->route()->getActionMethod(),
                'foto' => $usuarios[0]->foto
	        ];
        }

        return $arrayParametros;
    }
    public function eliminar_usuario($id){
        $errors = new MessageBag;
        $exitos = new MessageBag; 

        DB::update('update users set estado=?,updated_at=now() where id=?',["inactivo",$id]);
        $errors = new MessageBag;
        $exitos = new MessageBag;
    	$usuarios=DB::select('select   u.name as nombre_usuario,
							u.estado,
							p.ci,
							p.nombre,
							p.apellido_paterno,
							p.apellido_materno,

							(select STRING_AGG(r.nombre_rol, ? )  
							from tusuario_rol ur 
							join trol r on r.id_rol=ur.id_rol
							where ur.id_usuario=u.id)::varchar as roles,

							(select STRING_AGG(r.id_rol::varchar, ? )  
							from tusuario_rol ur 
							join trol r on r.id_rol=ur.id_rol
							where ur.id_usuario=u.id)::varchar as id_roles,
							u.id as id_usuario,
							u.password as contrasena,
                            u.email as correo
							from users u 
							join tpersona p on p.id_persona=u.id_persona
							where u.estado=? order by u.id desc ',["<br>","<br>","activo"]);

    	$personas=DB::select('select p.id_persona,p.nombre,p.apellido_paterno,p.apellido_materno,p.ci,p.celular,p.fecha_nacimiento from tpersona p where p.estado=?',["activo"]);

    	$arrayParametros=[
    		'usuarios'=>$usuarios,
    		'personas'=>$personas,
    		'errors' =>$errors,
            'exitos' =>$exitos,
            'accesos'=>$this->accesos
    	];

    	$exitos->add('exito','Exito!! Eliminado correctamente');

    	return view('usuario/lista_usuarios',$arrayParametros);
    }
}
