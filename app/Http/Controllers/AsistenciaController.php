<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cajas;
use App\Models\Movimiento_opcion;
use App\Models\Asistencia_tokens;
use App\Models\Asistencia;
use Auth;
use DB;

class AsistenciaController extends Controller
{
    public function asistencia($tken){
        $tk = $tken;
        return view('asistencia', ['token'=> $tk]);  
    }

    public function asistencia_admin(){
        return view('asistencia_admin');
    }

    public function get_dias_sesiones(Request $request){
        $fts = [];
        if(!empty($request->dia)) $fts[] = ['fecha_inicio', '=', $request->dia];
        if(!empty($request->estado)) $fts[] = ['estado', '=', $request->estado];
        $qry  = Asistencia_tokens::select("id","fecha_inicio","fecha_fin","estado","token","titulo");
        if(!empty($fts)){
            $qry->where($fts);
        }
        $qry->orderBy("fecha_inicio","DESC");
        $datos = $qry->get();
        return $datos; 
    }

    public function archivar_session_dia(Request $request){
        $opc = Asistencia_tokens::find($request->id);
        $opc->estado =  $request->estado;
        $opc->save(); 
        return response()->json(['name' => 'add', 'state' => 'ok']);
    }

    public function add_dias_sesiones(Request $request){
        $fecha = $request->fecha_inicio;
        $dias = $request->n_dias;
        $tit = '';
        for($i = 1; $i <= $dias; $i++){
            switch($i){
                case 1: $tit = 'primer registro de asistencia'; break;
                case 2: $tit = 'segundo registro de asistencia'; break;
                case 3: $tit = 'tercer registro de asistencia'; break;
            }
            $prefijo = 'IHV_';
            $tk =  uniqid($prefijo);
            $this->add_tokens($tit,$fecha,$tk,'on');
        }
        return response()->json(['name' => 'add', 'state' => 'ok']);
    }

    public function get_asistencia(Request $request){
        $fts = [];
        if(!empty($request->token_1)) $fts[] = [$request->token_1];
        if(!empty($request->token_2)) $fts[] = [$request->token_2];
        if(!empty($request->token_3)) $fts[] = [$request->token_3];
        $qry = Asistencia::select("id", "nombres", "cedula", "apellidos", "bootcamp", DB::raw("COUNT(*) AS n_asistencia"), "created_at");
        if(!empty($fts)){
            $qry->whereIn('tokeuser',$fts);
        }

        $qry->groupBy("cedula");
        $qry->orderBy("bootcamp", "ASC");
        $qry->orderBy("nombres", "ASC");
        $datos = $qry->get();
        return $datos; 

        // SELECT a.nombres, a.apellidos, a.bootcamp, COUNT(*) as registros, a.created_at 
        // FROM asistencia a WHERE a.tokeuser IN ('FTGMwQ_IHAOgCREIKDATIVO3','FTGMwQ_ISAOgSHEIKDAVIVO4')
        // GROUP BY cedula ORDER BY a.bootcamp, a.nombres;
        
        

    }

    public function add_tokens($tit,$fecha,$token,$est){
        $opc =  new Asistencia_tokens();
        $opc->token =  $token;
        $opc->titulo =  $tit;
        $opc->fecha_inicio =  $fecha;
        $opc->fecha_fin =  $fecha;
        $opc->estado =  $est;
        $opc->save();
    }

    public function query_token(Request $request){
        $fts[] = ['token', '=', $request->token];
        $fts[] = ['estado', '=', 'on'];
        $qry  = Asistencia_tokens::select("id","fecha_inicio","fecha_fin","estado","token","titulo");
        if(!empty($fts)){
            $qry->where($fts);
        }
        $datos = $qry->get();
        return $datos;   
    }

    public function marcar_asistencia(Request $request){
        // $this->validate(request(),[
        //     'movimiento' => 'required',
        //     'detalle' => 'required|max:100',
        // ]);
        $opc =  new Asistencia();
        $opc->cedula =  $request->cedula;
        $opc->bootcamp =  $request->bootcamp;
        $opc->nombres =  $request->nombres;
        $opc->apellidos =  $request->apellidos;
        $opc->id_token =  $request->id_token;
        $opc->tokeuser =   $request->tokeuser;
        $opc->save();
        return response()->json(['name' => 'add', 'state' => 'ok']);
    }

    public function verificar_asistencia(Request $request){
        $fts[] = ['tokeuser', '=', $request->token];
        $fts[] = ['cedula', '=', $request->cedula];
        $qry  = Asistencia::select("id","tokeuser","cedula","bootcamp","created_at");
        if(!empty($fts)){
            $qry->where($fts);
        }
        $datos = $qry->get();
        return $datos;  
    }
    
    public function salidas_ranking(Request $request){
        $fts[] = ['movimiento.id_user', '=', auth()->user()->id];
        $fts[] = ['movimiento.tipo', '=', 'out'];
        // $fts[] = ['movimiento.created_at', '>=', $request->fecha_i];
        // $fts[] = ['movimiento.created_at', '<=', $request->fecha_f];
        $qry = Movimiento::select(DB::raw("sum(movimiento.movimiento) as total"), 'movimiento.tipo','movimiento_opcion.opcion', 'movimiento.id_opcion');
        $qry->join('movimiento_opcion', 'movimiento.id_opcion', '=', 'movimiento_opcion.id');
        if(!empty($fts)){
            $qry->where($fts);
        }
        $qry->whereBetween('movimiento.created_at', [$request->fecha_i, $request->fecha_f]);
        $qry->orderBy("total","DESC");
        $qry->groupBy("movimiento.id_opcion");
        $datos = $qry->get();
        return $datos;

        // select sum(m.movimiento) as move, m.tipo, mo.opcion from movimiento m INNER JOIN movimiento_opcion mo ON m.id_opcion = mo.id where m.tipo = 'out' AND m.id_user = 1 GROUP BY (m.id_opcion) ORDER BY move DESC;
    }

    public function data_filtro(Request $request){
        // select DISTINCT(CONCAT(MONTH(m.created_at),",",YEAR(m.created_at))) as dato from movimiento m;
        $fts[] = ['movimiento.id_user', '=', auth()->user()->id];
        $qry = Movimiento::select(DB::raw("DISTINCT(CONCAT(MONTH(movimiento.created_at),',',YEAR(movimiento.created_at))) as dato"));
        if(!empty($fts)){
            $qry->where($fts);
        }
        $datos = $qry->get();
        return $datos;
    }
}
