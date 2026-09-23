<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facade\FlareClient\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function index(){
        return view('auth.login');  
    }

    public function denegado(){
        return view('auth.404')->with('denegado', 'No tienes  privilegios para estar en este espacio.');
    }

    public function store(Request $request){
        $this->validate($request,[
            'username' => 'required',
            'password' => 'required'
        ]);
        if(!auth()->attempt($request->only('username','password'), $request->remember)){
            return back()->with('mensaje','Credeciales incorrectas');
        }
        //dd(auth());
        
        return redirect()->route('dashboard');        
    }

    public function dashboard(){
        if(!auth()){
            return view('auth.login');  
            //return redirect()->route('login');
        }else{
            //$idtipo = auth()->user()->rol_id; 
            // echo auth()->user();
            return view('dashboard');
        }   
    }

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

    public function movimientos(){
        if(!auth()){
            return view('auth.login');  
            //return redirect()->route('login');
        }else{
            //$idtipo = auth()->user()->rol_id; 
            // echo auth()->user();
            return view('movimientos');
        }   
    }

    public function cliente_id(){
        return 'probando';
    }

    

    public function salir(){
        auth()->logout();
        return redirect()->route('login');
    }
}
