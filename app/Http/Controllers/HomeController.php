<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/
    protected $accesos;
    public function __construct(Request $request )
    {
        $this->accesos=[];
        $this->middleware('auth');
    }
    public function permitir($id,$ruta){
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
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $arrayParametros=[];

        $ruta=$request->route()->getActionMethod();
        $id=auth()->id();

        $permitir=$this->permitir($id,$ruta);
        
        $arrayParametros=[
            'accesos'=>$this->accesos,
        ];

        $usuario=DB::select(' select count(*)::integer as cantidad from users u where u.id=? and u.estado=? ',[$id,"inactivo"]);
        if((int)($usuario[0]->cantidad)>0){//bloquear a usuarios inactivos
          Auth::logout();
          return redirect('/login');
        }

        return view('home',$arrayParametros);
    }
}
