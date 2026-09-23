<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Setting;
use App\Models\Cajas;
use App\Models\Movimiento_opcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Auth;

class UserController extends Controller
{
    public function index(){
        $dato = array();
        $dato = User::all();
        return $dato;
    }

    public function create(){
        //echo Auth::user();
        if(Auth::user()->rol_id == '3'){
            $cliente_id = Auth::user()->cliente_id;
            $allUser = User::select('users.*', 'user_rol.rol','auth_cliente.cliente')
                  ->leftjoin('user_rol', 'user_rol.id', '=', 'users.rol_id')
                  ->leftjoin('auth_cliente', 'users.cliente_id', '=', 'auth_cliente.id')
                  ->where("cliente_id","=",$cliente_id)
                  ->get();
        }else{      
           $allUser = User::select('users.*', 'user_rol.rol','auth_cliente.cliente')
                  ->leftjoin('user_rol', 'user_rol.id', '=', 'users.rol_id')
                  ->leftjoin('auth_cliente', 'users.cliente_id', '=', 'auth_cliente.id')
                  ->get();
        }

        $usertipo = User_rol::all()->where ('id','!=','1');
        
        $clientes = AuthCliente::all();
        
        return view('auth.usuarios', compact('allUser'), compact('usertipo'),compact('clientes'));
    }

    public function usuariosAdd(){
        return view('auth.registro');
    }

    public function editarUser($id){ 
        $user =  User::findOrfail($id); //solo id validos
        $userX = User::select('users.*', 'user_rol.rol','auth_cliente.cliente')
        ->leftjoin('user_rol', 'user_rol.id', '=', 'users.rol_id')
        ->leftjoin('auth_cliente', 'users.cliente_id', '=', 'auth_cliente.id')            
        ->where('users.id',$id)->first();
        if(Auth::user()->rol_id == '3'){
            $cliente_id = Auth::user()->cliente_id;
            $clientes = AuthCliente::all()->where('id','=',$cliente_id);
            $roles = User_rol::all()->where('id','=','3');
        }else{
            $clientes = AuthCliente::all();
            $roles = User_rol::all()->where ('id','!=','1');
        }
        return view('auth.usuariosXid',compact('userX','clientes','roles'));
    }

    public function store(Request $request){
        $request->request->add(['username' => Str::slug($request->username)]);
        $this->validate(request(),[
            'email' => 'required|unique:users|email',
            'last_name' => 'required|max:50',
            'firts_name' => 'required|max:50',
            'password' => 'required|confirmed',
            'username' => 'required|unique:users|min:5|max:20',

        ]);
        $user = User::create([
            'username' => $request->username,
            'last_name' => $request->last_name,
            'firts_name' => $request->firts_name,
            'estado' => 1,
            'rol_id' => 3,
            'email' => $request->email,
            'img' => 'none.png',
            'password' =>  Hash ::make($request->password)
        ]);
        $us = new User();
        $idU = $us::latest()->first()->id;
        //cramos las configuraciones del usuario
        $opc =  new Setting();
        $opc->movimientos =  'Semana';
        $opc->paginador = '30';
        $opc->id_user = $idU;
        $opc->saldos_banco = 'si';
        $opc->save();
        // creamos la cajas de ejemplo
        $this->addCajas($idU,'Billetera');
        $this->addCajas($idU,'Banco');
        $this->addCajas($idU,'Nequi');
        // fin de cajas
        // creamos opciones de ejemplo
        $this->addOpcion($idU,'Salario','in');
        $this->addOpcion($idU,'Ingreso Ocasional','in');
        $this->addOpcion($idU,'Mercado','out');
        $this->addOpcion($idU,'Servicio público','out');
        $this->addOpcion($idU,'Comida por fuera','out');
        return redirect()->route('login'); 
    }
    public function addOpcion($id, $opc, $tipo){
        $op = new Movimiento_opcion();
        $op->opcion =  $opc;
        $op->tipo =  $tipo;
        $op->id_user =  $id;
        $op->save();
    }
    public function addCajas($id, $cj){
        $caja = new Cajas();
        $caja->caja = $cj;
        $caja->id_user = $id;
        $caja->save(); 
    }


    public function updateUser(Request $request, $id)
    {
        $this->validate(request(),[
            'last_name' => 'required|max:50',
            'firts_name' => 'required|max:50',
        ]);

        $us = User::find($id);
        $us->firts_name = $request->input('firts_name');
        $us->last_name = $request->input('last_name');
        if($us->rol_id != '3'){
            $us->rol_id = $request->input('rol_id');  
        }else{
            $us->cliente_id = $request->input('cliente_id');
        }
        $us->update();
        return redirect()->back()->with('status','Usuario modificado correctamente');
    }

    public function showChangePasswordGet() {
        return view('auth.change-password');
    }

    public function changePasswordPost(Request $request) {
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            // Current password and new password same
            return redirect()->back()->with("error","New Password cannot be same as your current password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:8|confirmed',
        ]);

        //Change Password
        
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        

        return redirect()->back()->with("success","Password successfully changed!");
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $user = User::find($id)->delete();
        return redirect()->route('users')
            ->with('success', 'AuthCliente deleted successfully');
    }
}
