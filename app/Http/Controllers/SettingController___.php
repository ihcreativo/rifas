<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
// use App\Models\SettingRango;
// use App\Models\SettingSalario;
// use App\Models\AuthCliente;
// use App\Models\Importacion_datos;
use DB;

class SettingController extends Controller
{
    public function setting(){
        if(!auth()){
            return view('auth.login');  
            //return redirect()->route('login');
        }else{
            //$idtipo = auth()->user()->rol_id; 
            // echo auth()->user();
            return view('setting');
        } 
    }

    public function ejemplo(){
        return view('graficos.ejemplo');
    }
   

    public function getData(){
        $user = auth()->user()->cliente_id;
        return AuthCliente::find($user);
        // return AuthCliente::select('auth_cliente.*')
        // ->where('id','=',$user)
        // ->get();
    }

    public function modificarCliente(Request $request)
    {
        $user = auth()->user()->cliente_id;
        try{
            DB::table('auth_cliente')
            ->where('id', $user)
            ->update(['cliente' => $request->cliente,
                        'nit' => $request->nit,
                        'contacto' => $request->contacto,
                        'correo' => $request->correo,
                        'direccion' => $request->direccion,
                        'numero_empleado' => $request->numero_empleado,
                        'salario_total_pago' => $request->salario_total_pago,
                        'rango_edad_activo' => $request->rango_edad_activo,
                    ]);
            return true;
        }catch(Exception $e){
            return false;
        }
       
            
        // $authCliente = new AuthCliente();
        // $authCliente->update($request->all(), $user);
    }

    public function getSalarios(){
        return SettingSalario::all();
    }

    public function getRangos(){
        $user = auth()->user()->cliente_id;
        $datos =  SettingRango::select('id','minimo','maximo','sigla','texto','orden')
                        ->where('cliente_id','=',$user)
                        ->get();
        return $datos;
    }

    public function delRango(Request $request){
        if(!empty($request->id)){
            SettingRango::find($request->id)
            ->delete();
        }
    }

    public function addRango(Request $request){
        $user = auth()->user()->cliente_id;
        $user = SettingRango::create([
            'minimo' => $request->minimo,
            'maximo' => $request->maximo,
            'orden' => $request->orden,
            'sigla' =>  $request->sigla,
            'texto' => $request->texto,
            'cliente_id' => $user
        ]);
        
    }
    public function result_query($ced){
        $fts = [];
       // $termino =  $request->search; 
        $termino = $ced;
        if($termino != ''){
            if(!empty($termino)){
                $fts[] = ['cedula', 'LIKE', '%'.$termino.'%', 'or'];
                $fts[] = ['nombre', 'LIKE', '%'.$termino.'%', 'or'];
            }
            $fts[] = ['cliente_id', '=', auth()->user()->cliente_id];
            $qry = Importacion_datos::select(DB::raw("DISTINCT(cedula) as cedula"), "nombre");
            if(!empty($fts)){
                $qry->where($fts);
            }
            $datos = $qry->get();
            //$data = ['datos' => $datos, 'termino' => $search];
            return view('dashboard.search',compact('datos','termino'));  
        } 
    }

    public function search(Request $request){
        return redirect()->route('result_query', $request->search); 
    }
   

    
    
}
