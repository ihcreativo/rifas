<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class WompiController extends Controller
{
    
    public function webhook(Request $request)
    {
        try {
    
            /*
            |--------------------------------------------------------------------------
            | 1. OBTENER DATOS DEL WEBHOOK
            |--------------------------------------------------------------------------
            */
    
            $evento = $request->all();
    
            $transaction = $evento['data']['transaction'] ?? null;
            $signature = $evento['signature'] ?? null;
            $timestamp = $evento['timestamp'] ?? null;
    
            if (!$transaction || !$signature || !$timestamp) {
    
                Log::error('WOMPI: DATOS INCOMPLETOS');
    
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de webhook incompletos'
                ], 400);
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | 2. OBTENER DATOS DE LA FIRMA
            |--------------------------------------------------------------------------
            */
    
            $properties = $signature['properties'] ?? [];
            $checksumRecibido = $signature['checksum'] ?? null;
    
            if (empty($properties) || !$checksumRecibido) {
    
                Log::error('WOMPI: FIRMA INCOMPLETA');
    
                return response()->json([
                    'success' => false,
                    'message' => 'Firma incompleta'
                ], 400);
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | 3. CONSTRUIR CADENA PARA VALIDAR CHECKSUM
            |--------------------------------------------------------------------------
            */
    
            $cadena = '';
    
            foreach ($properties as $property) {
    
                $partes = explode('.', $property);
    
                // Las propiedades de Wompi apuntan dentro de "data"
                $valor = $evento['data'];
    
                foreach ($partes as $parte) {
    
                    if (
                        !is_array($valor) ||
                        !array_key_exists($parte, $valor)
                    ) {
    
                        Log::error('WOMPI: PROPIEDAD NO ENCONTRADA', [
                            'property' => $property
                        ]);
    
                        return response()->json([
                            'success' => false,
                            'message' => 'Propiedad de firma no encontrada'
                        ], 400);
                    }
    
                    $valor = $valor[$parte];
                }
    
                $cadena .= $valor;
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | 4. TIMESTAMP + EVENT SECRET
            |--------------------------------------------------------------------------
            */
    
            $cadena .= $timestamp;
            $cadena .= config('services.wompi.events_secret');
    
    
            /*
            |--------------------------------------------------------------------------
            | 5. CALCULAR CHECKSUM
            |--------------------------------------------------------------------------
            */
    
            $checksumCalculado = hash('sha256', $cadena);
    
    
            /*
            |--------------------------------------------------------------------------
            | 6. VALIDAR CHECKSUM
            |--------------------------------------------------------------------------
            */
    
            if (
                !hash_equals(
                    strtolower($checksumRecibido),
                    strtolower($checksumCalculado)
                )
            ) {
    
                Log::error('WOMPI: CHECKSUM INVALIDO', [
                    'reference' => $transaction['reference'] ?? null
                ]);
    
                return response()->json([
                    'success' => false,
                    'message' => 'Checksum inválido'
                ], 401);
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | 7. DATOS DE LA TRANSACCIÓN
            |--------------------------------------------------------------------------
            */
    
            $referencia = $transaction['reference'] ?? null;
            $estado = $transaction['status'] ?? null;
            $montoPagado = $transaction['amount_in_cents'] ?? null;
            $moneda = $transaction['currency'] ?? null;
    
            if (
                !$referencia ||
                !$estado ||
                $montoPagado === null
            ) {
    
                Log::error('WOMPI: DATOS DE TRANSACCION INCOMPLETOS', [
                    'reference' => $referencia
                ]);
    
                return response()->json([
                    'success' => false
                ], 400);
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | 8. VALIDAR MONEDA
            |--------------------------------------------------------------------------
            */
    
            if ($moneda !== 'COP') {
    
                Log::error('WOMPI: MONEDA INVALIDA', [
                    'reference' => $referencia,
                    'currency' => $moneda
                ]);
    
                return response()->json([
                    'success' => false,
                    'message' => 'Moneda inválida'
                ], 400);
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | 9. BUSCAR LOS NÚMEROS DE LA RESERVA
            |--------------------------------------------------------------------------
            */
    
            $numeros = \App\Models\RifaNumero::where(
                'wompi_reference',
                $referencia
            )->get();
    
    
            /*
            |--------------------------------------------------------------------------
            | 10. REFERENCIA NO ENCONTRADA
            |--------------------------------------------------------------------------
            */
    
            if ($numeros->isEmpty()) {
    
                Log::error('WOMPI: REFERENCIA NO ENCONTRADA', [
                    'reference' => $referencia
                ]);
    
                // Evitar reintentos innecesarios de Wompi
                return response()->json([
                    'success' => true
                ], 200);
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | 11. OBTENER LA RIFA
            |--------------------------------------------------------------------------
            */
    
            $rifa = \App\Models\Rifa::find(
                $numeros->first()->rifa_id
            );
    
            if (!$rifa) {
    
                Log::error('WOMPI: RIFA NO ENCONTRADA', [
                    'reference' => $referencia,
                    'rifa_id' => $numeros->first()->rifa_id
                ]);
    
                return response()->json([
                    'success' => false,
                    'message' => 'Rifa no encontrada'
                ], 400);
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | 12. OBTENER VALOR DEL NÚMERO
            |--------------------------------------------------------------------------
            |
            | El precio se obtiene directamente de:
            |
            | rifas.valor_opcion
            |
            */
    
            $valorNumero = (int) $rifa->valor_opcion;
    
    
            /*
            |--------------------------------------------------------------------------
            | 13. VALIDAR VALOR DE LA RIFA
            |--------------------------------------------------------------------------
            */
    
            if ($valorNumero <= 0) {
    
                Log::error('WOMPI: VALOR DE RIFA INVALIDO', [
                    'reference' => $referencia,
                    'rifa_id' => $rifa->id,
                    'valor_opcion' => $rifa->valor_opcion
                ]);
    
                return response()->json([
                    'success' => false,
                    'message' => 'Valor de la rifa inválido'
                ], 400);
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | 14. CALCULAR MONTO ESPERADO
            |--------------------------------------------------------------------------
            */
    
            $cantidadNumeros = $numeros->count();
    
            $montoEsperado = $cantidadNumeros
                * $valorNumero
                * 100;
    
    
            /*
            |--------------------------------------------------------------------------
            | 15. VALIDAR MONTO PAGADO
            |--------------------------------------------------------------------------
            */
    
            if ((int) $montoPagado !== (int) $montoEsperado) {
    
                Log::error('WOMPI: MONTO INVALIDO', [
                    'reference' => $referencia,
                    'rifa_id' => $rifa->id,
                    'cantidad_numeros' => $cantidadNumeros,
                    'valor_numero' => $valorNumero,
                    'monto_pagado' => $montoPagado,
                    'monto_esperado' => $montoEsperado
                ]);
    
                return response()->json([
                    'success' => false,
                    'message' => 'Monto de pago incorrecto'
                ], 400);
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | 16. SOLO PROCESAR TRANSACCIONES APROBADAS
            |--------------------------------------------------------------------------
            */
    
            if ($estado !== 'APPROVED') {
    
                Log::error('WOMPI: TRANSACCION NO APROBADA', [
                    'reference' => $referencia,
                    'status' => $estado
                ]);
    
                return response()->json([
                    'success' => true
                ], 200);
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | 17. ACTUALIZAR NÚMEROS
            |--------------------------------------------------------------------------
            */
    
            $actualizados = 0;
    
            foreach ($numeros as $numero) {
    
                // Evitar reprocesar pagos ya confirmados
                if (
                    $numero->estado === 'pagado' &&
                    $numero->estado_pago === 'aprobado'
                ) {
                    continue;
                }
    
                $numero->estado = 'pagado';
                $numero->estado_pago = 'aprobado';
                $numero->fecha_pago = now();
    
                $numero->save();
    
                $actualizados++;
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | 18. REGISTRAR PAGO CONFIRMADO
            |--------------------------------------------------------------------------
            */
    
            Log::error('WOMPI: PAGO CONFIRMADO', [
                'reference' => $referencia,
                'rifa_id' => $rifa->id,
                'numeros' => $numeros->pluck('numero')->toArray(),
                'cantidad' => $cantidadNumeros,
                'valor_numero' => $valorNumero,
                'monto' => $montoPagado,
                'actualizados' => $actualizados
            ]);
    
    
            /*
            |--------------------------------------------------------------------------
            | 19. RESPUESTA A WOMPI
            |--------------------------------------------------------------------------
            */
    
            return response()->json([
                'success' => true
            ], 200);
    
    
        } catch (\Exception $e) {
    
            Log::error('WOMPI: ERROR WEBHOOK', [
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
    
            return response()->json([
                'success' => false
            ], 500);
        }
    }
    
   public function crearPago(Request $request)
    {
        $request->validate([
            'monto' => 'required|integer|min:1',
            'referencia' => 'required|string|max:100',
        ]);

        try {

            /*
            |--------------------------------------------------------------------------
            | 1. BUSCAR LOS NÚMEROS DE LA RESERVA
            |--------------------------------------------------------------------------
            */

            $numeros = \App\Models\RifaNumero::where(
                'wompi_reference',
                $request->referencia
            )->get();

            if ($numeros->isEmpty()) {

                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la reserva.'
                ], 404);
            }


            /*
            |--------------------------------------------------------------------------
            | 2. OBTENER RIFA
            |--------------------------------------------------------------------------
            */

            $rifa = \App\Models\Rifa::find(
                $numeros->first()->rifa_id
            );

            if (!$rifa) {

                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la rifa.'
                ], 404);
            }


            /*
            |--------------------------------------------------------------------------
            | 3. OBTENER VENDEDOR
            |--------------------------------------------------------------------------
            */

            $vendedor = \App\Models\User::find(
                $numeros->first()->id_vendedor
            );

            if (!$vendedor) {

                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el vendedor.'
                ], 404);
            }


            /*
            |--------------------------------------------------------------------------
            | 4. VALIDAR TOKENS
            |--------------------------------------------------------------------------
            */

            if (!$rifa->token || !$vendedor->token) {

                return response()->json([
                    'success' => false,
                    'message' => 'La rifa o el vendedor no tienen token configurado.'
                ], 400);
            }


            /*
            |--------------------------------------------------------------------------
            | 5. MONTO
            |--------------------------------------------------------------------------
            */

            $montoPesos = $request->monto;

            $montoCentavos = $montoPesos * 100;

            $referencia = $request->referencia;

            $moneda = 'COP';


            /*
            |--------------------------------------------------------------------------
            | 6. FIRMA DE INTEGRIDAD WOMPI
            |--------------------------------------------------------------------------
            */

            $cadena = $referencia
                . $montoCentavos
                . $moneda
                . config('services.wompi.integrity_secret');

            $firma = hash('sha256', $cadena);


            /*
            |--------------------------------------------------------------------------
            | 7. URL DE RETORNO
            |--------------------------------------------------------------------------
            */

            $redirectUrl = 'https://rifa.ishevi.com/r/'
                . $rifa->token
                . '/'
                . $vendedor->token;


            /*
            |--------------------------------------------------------------------------
            | 8. RESPUESTA
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,

                'public_key' => config('services.wompi.public_key'),

                'reference' => $referencia,

                'amount_in_cents' => $montoCentavos,

                'currency' => $moneda,

                'signature' => $firma,

                'redirect_url' => $redirectUrl,

                'checkout_url' => 'https://checkout.wompi.co/p/',
            ]);

        } catch (\Exception $e) {

            Log::error('Error creando pago Wompi', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'No fue posible crear el pago.'
            ], 500);
        }
    }
    
}