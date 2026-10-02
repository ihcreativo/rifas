<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WompiController;
use App\Http\Controllers\SettingController;

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


Route::get('/changePassword', [UserController::class, 'showChangePasswordGet'])->middleware('auth')->name('changePasswordGet');
Route::post('/changePassword',[UserController::class, 'changePasswordPost'])->middleware('auth')->name('changePasswordPost');

// Rifa

Route::get('/rifa/{id}', [RifaController::class, 'show']);
Route::post('/rifa_numeros', [RifaController::class, 'index']);
Route::post('/rifa_reservar', [RifaController::class, 'reservar']);
Route::post('/saveRifa', [RifaController::class, 'saveRifa']);
Route::post('/mis-rifas', [RifaController::class, 'getRifasByUser']);
Route::post('/rifas/repartir-numeros', [RifaController::class, 'repartirNumeros']);

//rifa por vendedor
Route::get('/r/{tr}/{tv}', [RifaController::class, 'showRifa']);

Route::get('/usuarios', [UserController::class, 'index']);
Route::get('/mis-participantes', [UserController::class, 'show'])->name('participantes');
Route::post('/usuarios', [UserController::class, 'store']);
Route::put('/usuarios/{id}', [UserController::class, 'update']);
Route::put('/usuarios/{id}/estado', [UserController::class, 'cambiarEstado']);
Route::get('/rifas/{id}/vendedores-numeros', [RifaController::class, 'vendedoresConNumeros']);
//imagenes
Route::post('/rifas/subir-imagenes', [RifaController::class, 'subirImagenes']);
Route::get('/rifas/{id}/imagenes', [RifaController::class, 'imagenes']);
Route::delete('/rifas/imagenes/{id}', [RifaController::class, 'eliminarImagen']);

//admin vendedor
Route::get( '/rifas-vendedor', [RifaController::class, 'getRifasByVendedor'] );
Route::post(
    '/rifas-vendedor/numero/{id}/estado',
    [RifaController::class, 'cambiarEstadoNumero']
)->middleware('auth');
Route::post(
    '/rifas/transferir-numeros',
    [RifaController::class, 'transferirNumeros']
)->middleware('auth');

// Route::get(
//     '/rifas-vendedor/numero/{id}/confirmacion-whatsapp',
//     [RifaController::class, 'datosConfirmacionWhatsApp']
// );
Route::get(
    '/rifas-vendedor/numero/{id}/confirmacion-whatsapp',
    [RifaController::class, 'datosConfirmacionWhatsApp']
);

Route::get(
    '/rifas-vendedor/numero/{id}/cobro-whatsapp',
    [RifaController::class, 'datosConfirmacionWhatsApp']
);









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


