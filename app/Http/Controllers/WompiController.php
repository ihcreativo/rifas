<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
    
    public function crearPago(Request $request)
    {
        $request->validate([
            'monto' => 'required|integer|min:1',
        ]);

        try {

            // Monto recibido en pesos colombianos
            $montoPesos = $request->monto;

            // Wompi trabaja en centavos
            $montoCentavos = $montoPesos * 100;

            // Referencia única
            $referencia = 'RIFA-' . strtoupper(Str::random(12));

            // Moneda
            $moneda = 'COP';

            // Firma de integridad
            $cadena = $referencia
                . $montoCentavos
                . $moneda
                . config('services.wompi.integrity_secret');

            $firma = hash('sha256', $cadena);

            return response()->json([
                'success' => true,

                'public_key' => config('services.wompi.public_key'),

                'reference' => $referencia,

                'amount_in_cents' => $montoCentavos,

                'currency' => $moneda,

                'signature' => $firma,

                'checkout_url' => 'https://checkout.wompi.co/p/',
            ]);

        } catch (\Exception $e) {

            Log::error('Error creando pago Wompi', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'No fue posible crear el pago.'
            ], 500);
        }
    }
    
    
}