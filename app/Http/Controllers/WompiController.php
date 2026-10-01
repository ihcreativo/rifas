<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WompiController extends Controller
{
    public function webhook(Request $request)
    {
        Log::info('WOMPI WEBHOOK RECIBIDO', [
            'data' => $request->all(),
        ]);

        return response()->json([
            'success' => true
        ], 200);
    }
}