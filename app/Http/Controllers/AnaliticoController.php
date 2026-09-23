<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cajas;
use App\Models\Movimiento_opcion;
use App\Models\Movimiento;
use Auth;
use DB;

class AnaliticoController extends Controller
{
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
