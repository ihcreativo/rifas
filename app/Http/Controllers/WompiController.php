<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WompiController extends Controller
{
    public function webhook(Request $request)
    {
        \Log::info('Wompi Webhook recibido', $request->all());

        return response()->json([
            'success' => true
        ]);
    }
}