<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WompiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/wompi/webhook', [WompiController::class, 'webhook']);
Route::post('/wompi/crear-pago', [WompiController::class, 'crearPago']);
