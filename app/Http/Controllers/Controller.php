<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;//juan
use DB;//juan

class Controller extends BaseController
{
	protected $accesos=[];//juan
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function verificarAcceso(){//juan
		//$this->middleware('auth');
        $this->middleware(function (Request $request, $next) {
            $id=auth()->id();

            $usuario=DB::select(' select count(*)::integer as cantidad from users u where u.id=? and u.estado=? ',[$id,"inactivo"]);
            if((int)($usuario[0]->cantidad)>0){//bloquear a usuarios inactivos
              Auth::logout();
              return redirect('/login');
            }
            
            $ruta=$request->route()->getActionMethod();
            $permitir=$this->permitir($id,$ruta);
            
            if (!\Auth::check() || $permitir!=true) {

                return redirect('/login');
            }
            return $next($request);
        });
	}
    public function permitir($id,$ruta){//juan
        $bandera=false;
        $permisos=DB::select("
                            WITH permisos AS(
                            select ur.id_rol,r.id_permisos,ur.id_usuario 
                            from trol r
                            join tusuario_rol ur on ur.id_rol=r.id_rol
                            )
                            select p.id_permiso,
                            p.ruta,
                            p.nombre_acceso, 
                            lower(p.nombre_acceso) as boton,
                            case when  p2.ruta is not null then true else false end::varchar as acceso,
                            p.codigo
                            from tpermiso p
                            left join tpermiso p2 on p2.id_permiso=p.id_permiso 
                            and p.id_permiso::varchar in (select unnest(per.id_permisos) from permisos per where per.id_usuario=? )
                            ",[$id]);

        for ($i=0; $i < count($permisos); $i++) { 
               $this->accesos[$permisos[$i]->ruta]=$permisos[$i]->acceso; 
        }
        $permisos=DB::select("WITH permisos AS(
                            select ur.id_rol,r.id_permisos,ur.id_usuario 
                            from trol r
                            join tusuario_rol ur on ur.id_rol=r.id_rol
                            )
                            select count(*) as cantidad
                            from tpermiso p
                            join tpermiso p2 on p2.id_permiso=p.id_permiso 
                            and p.id_permiso::varchar in (select unnest(per.id_permisos) from permisos per where per.id_usuario=?)
                            where p.ruta::varchar= ?::varchar ",[$id,$ruta]);

        if((int)($permisos[0]->cantidad)>0){
           $bandera =true;
        }
        return $bandera;
    }
}
