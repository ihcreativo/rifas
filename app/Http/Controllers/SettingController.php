<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

use Auth;
use DB;

class SettingController extends Controller
{
    public function getSetting(){
        $fts = [];
        $fts[] = ['id_user', '=', auth()->user()->id];
        $qry = Setting::select('id','movimientos','paginador','saldos_banco','id_user');
        //$datos = Cajas::all();
        if(!empty($fts)){
            $qry->where($fts);
        }
        $qry->limit(1);
        $datos = $qry->get();
        return $datos;
    }
    public function updateSetting(Request $request){
        $campo = '';
        $valor = '';
        if(!empty($request->movimientos)) {$campo = 'movimientos'; $valor = $request->movimientos;}
        if(!empty($request->saldos_banco)) {$campo = 'saldos_banco'; $valor = $request->saldos_banco;}
        if(!empty($request->paginador)){$campo = 'paginador'; $valor = $request->paginador;}
        if($valor != ''){
            Setting::where("id_user", auth()->user()->id)->update([$campo => $valor]); //update

            return response()->json(['name' => 'update', 'state' => 'ok', 'datos' => Setting::select('id','movimientos', 'paginador', 'saldos_banco')->where('id_user', '=', auth()->user()->id)->get()]);  
        }else{
            return response()->json(['name' => 'update', 'state' => 'no']);  
        }

    }
}
