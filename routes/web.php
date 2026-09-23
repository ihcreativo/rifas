<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AnaliticoController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\RifaController;

Route::get('/404', [LoginController::class, 'denegado'])->middleware('auth')->name('denegado');
Route::get('/', [LoginController::class, 'index'])->name('login.index');
Route::get('/login', [LoginController ::class,'index'])->name('login');
Route::post('/login', [LoginController::class,'store']);

Route::get('/salir', [LoginController::class, 'salir'])->name('salir');
Route::get('/dashboard',[LoginController::class,'dashboard'])->middleware('auth')->name('dashboard');
Route::get('/movimientos',[LoginController::class,'movimientos'])->middleware('auth')->name('movimientos');
Route::get('/configuracion',[LoginController::class,'setting'])->middleware('auth')->name('setting');

Route::post('/search',[SettingController::class, 'search'])->name('search');
Route::get('/result_query/{ced}',[SettingController::class, 'result_query'])->name('result_query');

Route::get('/users/list',[UserController::class,'create'])->middleware('auth')->name('users');
Route::get('/registro',[UserController::class,'usuariosAdd'])->name('usuariosAdd');
Route::post('/registro',[UserController::class,'store'])->name('agregarUsuario');
Route::get('/users/edit/{id}',[UserController::class,'editarUser'])->middleware('auth')->name('usuarioX');
Route::post('/users/edit/{id}',[UserController::class,'updateUser'])->middleware('auth')->name('updateUser');
Route::post('/users/delete/{id}',[UserController::class,'destroy'])->middleware('auth')->name('usersDelete');

Route::get('/changePassword', [UserController::class, 'showChangePasswordGet'])->middleware('auth')->name('changePasswordGet');
Route::post('/changePassword',[UserController::class, 'changePasswordPost'])->middleware('auth')->name('changePasswordPost');

// rutas vue
Route::post('/cajas-vue', [MovimientoController::class, 'Cajas']);
Route::post('/opcion-vue', [MovimientoController::class, 'MovimientoOpcion']);
Route::post('/get_saldo-vue', [MovimientoController::class, 'getSaldo']);

Route::post('/registrarMovimiento-vue', [MovimientoController::class, 'registrarMovimiento']);
Route::post('/movimientos-vue', [MovimientoController::class, 'Movimientos']);
Route::post('/saldo_cajas-vue', [MovimientoController::class, 'saldoCajas']);
Route::post('/agregar_caja-vue', [MovimientoController::class, 'agregar_caja']);
Route::post('/eliminar_caja-vue', [MovimientoController::class, 'eliminar_caja']);
Route::post('/agregar_opcion-vue', [MovimientoController::class, 'agregar_opcion']);
Route::post('/eliminar_opcion-vue', [MovimientoController::class, 'eliminar_opcion']);
Route::post('/eliminar_movimiento-vue', [MovimientoController::class, 'eliminar_movimiento']);

// DashBoard principa setting
Route::post('/nivelar_caja-vue', [MovimientoController::class, 'nivelar_caja']);
Route::post('/getSetting-vue', [SettingController::class, 'getSetting']);
Route::post('/update_setting-vue', [SettingController::class, 'updateSetting']);
Route::post('/trasladar_dinero-vue', [MovimientoController::class, 'trasladarDinero']);

// Analico 
Route::post('/salidas_ranking-vue', [AnaliticoController::class, 'salidas_ranking']);
Route::post('/data_filtro-vue', [AnaliticoController::class, 'data_filtro']);





Route::get('/asistencia/{tken}', [AsistenciaController::class, 'asistencia']);
Route::post('/query_token', [AsistenciaController::class, 'query_token']);
Route::post('/marcar_asistencia', [AsistenciaController::class, 'marcar_asistencia']);
Route::post('/verificar_asistencia', [AsistenciaController::class, 'verificar_asistencia']);
// Administracion
Route::get('/asistencia_admin', [AsistenciaController::class, 'asistencia_admin']);
Route::post('/get_dias_sesiones', [AsistenciaController::class, 'get_dias_sesiones']);
Route::post('/add_dias_sesiones', [AsistenciaController::class, 'add_dias_sesiones']);
Route::post('/get_asistencia', [AsistenciaController::class, 'get_asistencia']);
Route::post('/archivar_session_dia', [AsistenciaController::class, 'archivar_session_dia']);

// Rifa

Route::get('/rifa/{id}', [RifaController::class, 'show']);
Route::post('/rifa_numeros', [RifaController::class, 'index']);
Route::post('/rifa_reservar', [RifaController::class, 'reservar']);










/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


