<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cajas;
use App\Models\Movimiento_opcion;
use App\Models\Movimiento;
use Auth;
use DB;

class MovimientoController extends Controller
{
    public function Cajas(){
        $fts = [];
        $fts[] = ['id_user', '=', auth()->user()->id];
        $qry = Cajas::select('caja','id_user','id',DB::raw("(select count(*) from movimiento  WHERE movimiento.id_caja = cajas.id) as move" ));
        //$datos = Cajas::all();
        if(!empty($fts)){
            $qry->where($fts);
        }
        // // $qry->groupBy("ano");
        $qry->orderBy("caja","ASC");
        $datos = $qry->get();
        // print('isaias------------herazo');
        return $datos;
    }

    public function Movimientos(Request $request){
        $pag = $request->n_registro;
        $salto = 0;
        if(($request->pagina > 1)) $salto = (($request->pagina - 1)* $pag);
        $datos = Movimiento::select('movimiento.id','movimiento.movimiento','movimiento.detalle','movimiento.tipo','cajas.caja','movimiento.id_opcion','movimiento.id_caja','movimiento_opcion.opcion','movimiento.created_at',DB::raw("WEEK(movimiento.created_at,1) as semana"), DB::raw("DAYOFWEEK(movimiento.created_at) as dia_semana"),  DB::raw("SUBSTRING(movimiento.created_at, 1, 10) as fecha"),  DB::raw("SUBSTRING(movimiento.created_at, 11, 9) as hora"))
                ->join('movimiento_opcion', 'movimiento.id_opcion', '=', 'movimiento_opcion.id')
                ->join('cajas', 'movimiento.id_caja', '=', 'cajas.id')
                ->where("movimiento.id_user", "=",  auth()->user()->id)
                ->orderBy("movimiento.created_at","DESC")
                ->skip($salto)->take($pag)
                ->get();
        return $datos;
    }
    public function saldoCajas(){
        $datos = Movimiento::select('movimiento.tipo','movimiento.id_caja','cajas.caja',DB::raw("sum(`movimiento`.`movimiento`) as movimiento"))
                ->join('cajas', 'movimiento.id_caja', '=', 'cajas.id')
                ->where("movimiento.id_user", "=", auth()->user()->id)
                ->groupBy("movimiento.id_caja", "movimiento.tipo")
                ->get();
        return $datos;
    }

    function getSaldo(){
        $datos = Movimiento::select('movimiento.tipo',DB::raw("sum(`movimiento`.`movimiento`) as movimiento"))
        ->where("movimiento.id_user", "=", auth()->user()->id)
        ->groupBy("movimiento.tipo")
        ->get();
        return $datos;
    }

    function trasladarDinero(Request $request){
        $id_inicio = $request->id_inicio;
        $id_fin = $request->id_fin;
        $traslado = $request->traslado;
        //de donde sale
        $id_opcion_out = $this->getOpcionID('out', 'Traslado');
        $opc =  new Movimiento();
        $opc->movimiento =  $traslado;
        $opc->tipo = 'out';
        $opc->detalle = 'Traslado automatico';
        $opc->id_opcion =  $id_opcion_out;
        $opc->id_caja =  $id_inicio;
        $opc->id_user = auth()->user()->id;
        $opc->save();
        //a donde llega
        $id_opcion_in = $this->getOpcionID('in', 'Traslado');
        $opc1 =  new Movimiento();
        $opc1->movimiento =  $traslado;
        $opc1->tipo = 'in';
        $opc1->detalle = 'Traslado automatico';
        $opc1->id_opcion =  $id_opcion_in;
        $opc1->id_caja =  $id_fin;
        $opc1->id_user = auth()->user()->id;
        $opc1->save();
        return response()->json(['name' => 'add', 'state' => 'ok']); 
    }

    function nivelar_caja(Request $request){
        $id_opcion = $this->getOpcionID('out','Perdida_controlada');
        if($id_opcion != ''){
            $id_caja = $request->id;
            $saldo = $request->saldo;
            $saldo_r = $request->saldo_r;
            $opc =  new Movimiento();
            $opc->movimiento =  $saldo-$saldo_r;
            $opc->detalle =  'Perdida_controlada';
            $opc->tipo =  'out';
            $opc->id_caja =  $id_caja;
            $opc->id_opcion =  $id_opcion;
            $opc->id_user =  auth()->user()->id;
            $opc->save();
            return response()->json(['name' => 'add', 'state' => 'ok']);   
        }else{
            return response()->json(['name' => 'add', 'state' => 'no']); 
        }
    }

    public function getOpcionID($tipo, $opcion){
        $name = $opcion;
        $x = '';
        $fts = [];
        $fts[] = ['id_user', '=', auth()->user()->id];
        $fts[] = ['opcion', 'LIKE', $name];
        $fts[] = ['tipo', 'LIKE', $tipo];
        $qry = Movimiento_opcion::select('opcion','tipo','id_user','id');
        if(!empty($fts)){
            $qry->where($fts);
        }
        $qry->limit(1);
        $datos = $qry->get();
        if(count($datos) > 0){
            $x = $datos[0]->id;
        }else{
            $opc =  new Movimiento_opcion();
            $opc->opcion =  $name;
            $opc->tipo = $tipo;
            $opc->id_user = auth()->user()->id;
            $opc->save();
            //buscamos el ultimo Id insertado en tabla Movimiento_opcion
            $x = $opc::latest()->first()->id;
        }
        return $x;
    }

    public function getOpcionID__(){
        $name = 'Perdida_controlada';
        $x = '';
        $fts = [];
        $fts[] = ['id_user', '=', auth()->user()->id];
        $fts[] = ['opcion', 'LIKE', $name];
        $qry = Movimiento_opcion::select('opcion','tipo','id_user','id');
        if(!empty($fts)){
            $qry->where($fts);
        }
        $qry->limit(1);
        $datos = $qry->get();
        if(count($datos) > 0){
            $x = $datos[0]->id;
        }else{
            $opc =  new Movimiento_opcion();
            $opc->opcion =  $name;
            $opc->tipo = 'out';
            $opc->id_user = auth()->user()->id;
            $opc->save();
            //buscamos el ultimo Id insertado en tabla Movimiento_opcion
            $x = $opc::latest()->first()->id;
        }
        return $x;
    }

    public function MovimientoOpcion(){
        $fts = [];
        $fts[] = ['id_user', '=', auth()->user()->id];
        $qry = Movimiento_opcion::select('opcion','tipo','id_user','id');
        //$datos = Cajas::all();
        if(!empty($fts)){
            $qry->where($fts);
        }
        // // $qry->groupBy("ano");
        $qry->orderBy("opcion","ASC");
        $datos = $qry->get();
        // print('isaias------------herazo');
        return $datos;
    }

    public function registrarMovimiento(Request $request){
        $this->validate(request(),[
            'movimiento' => 'required',
            'detalle' => 'required|max:100',
        ]);
        $opc =  new Movimiento();
        $opc->movimiento =  $request->movimiento;
        $opc->detalle =  $request->detalle;
        $opc->tipo =  $request->tipo;
        $opc->id_caja =  $request->id_caja;
        $opc->id_opcion =  $request->id_opcion;
        $opc->id_user =  auth()->user()->id;
        $opc->save();
        return response()->json(['name' => 'add', 'state' => 'ok']);   
    }

    public function agregar_caja(Request $request){
        $this->validate(request(),[
            'caja' => 'required'
        ]);
        $opc =  new Cajas();
        $opc->caja =  $request->caja;
        $opc->id_user =  auth()->user()->id;
        $opc->save();
        return response()->json(['name' => 'add', 'state' => 'ok']);
    }

    public function eliminar_caja(Request $request){
        $idU = auth()->user()->id;
        $opc = Cajas::findOrFail($request->id);
        $opc->delete();
        return response()->json(['name' => 'add', 'state' => 'ok']);
    }

    public function agregar_opcion(Request $request){
        $this->validate(request(),[
            'opcion' => 'required',
            'tipo' => 'required'
        ]);
        $opc =  new Movimiento_opcion();
        $opc->opcion =  $request->opcion;
        $opc->tipo =  $request->tipo;
        $opc->id_user =  auth()->user()->id;
        $opc->save();
        return response()->json(['name' => 'add', 'state' => 'ok']);
    }

    public function eliminar_opcion(Request $request){
        $idU = auth()->user()->id;
        $opc = Movimiento_opcion::findOrFail($request->id);
        $opc->delete();
        return response()->json(['name' => 'add', 'state' => 'ok']);
    }

    public function eliminar_movimiento(Request $request){
        $idU = auth()->user()->id;
        $opc = Movimiento::findOrFail($request->id);
        $opc->delete();
        return response()->json(['name' => 'add', 'state' => 'ok']);
    }

}
