<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rifa;
use App\Models\RifaNumero;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class RifaController extends Controller
{

    public function index(Request $request)
    {
        $numeros = RifaNumero::where('rifa_id', $request->rifa_id)
            ->orderBy('numero', 'asc')
            ->get([
                'id',
                'rifa_id',
                'numero',
                'estado',
            ]);

        return response()->json([
            'success' => true,
            'numeros' => $numeros
        ]);
    }

    /**
     * Mostrar información de un número.
     */
    public function show($id)
    {
        return view('rifa_client', ['id'=> $id]);  
    }

    /**
     * Reservar un número.
     */
    public function reservar(Request $request)
    {
        $request->validate([
            'rifa_id' => 'required|integer',
            'nombre_cliente' => 'required|string|max:150',
            'whatsapp_cliente' => 'required|string|max:30',
            'numeros' => 'required|array|min:1',
            'numeros.*' => 'required|integer',
        ]);

        try {

            DB::beginTransaction();

            /*
            | Verificamos que todos los números
            | estén disponibles.
            */
            $numeros = RifaNumero::where('rifa_id', $request->rifa_id)
                ->whereIn('id', $request->numeros)
                ->lockForUpdate()
                ->get();

            if ($numeros->count() != count($request->numeros)) {

                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Uno o más números no pertenecen a esta rifa.'
                ], 422);
            }

            /*
            | Verificamos disponibilidad.
            */
            $noDisponibles = $numeros->where('estado', '!=', 'disponible');

            if ($noDisponibles->count() > 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Uno o más números ya no están disponibles. Por favor, actualice la página e intente nuevamente.'
                ], 422);
            }

            /*
            | Reservamos los números.
            */
            RifaNumero::whereIn('id', $request->numeros)
                ->where('rifa_id', $request->rifa_id)
                ->update([
                    'estado' => 'reservado',
                    'nombre' => $request->nombre_cliente,
                    'whatsapp' => $request->whatsapp_cliente,
                    'fecha_reserva' => now()
                ]);

            DB::commit();
            try {

                Mail::raw(
                    "NUEVA RESERVA DE RIFA\n\n" .
                    "Cliente: " . $request->nombre_cliente . "\n" .
                    "WhatsApp: " . $request->whatsapp_cliente . "\n" .
                    "Rifa: " . $request->rifa_id . "\n" .
                    "Números: " .
                    $numeros->pluck('numero')
                        ->map(function ($numero) {
                            return str_pad($numero, 2, '0', STR_PAD_LEFT);
                        })
                        ->implode(', ') . "\n" .
                    "Fecha: " . now()->format('d/m/Y H:i:s'),

                    function ($message) {
                        $message->to('isaiasherazo@gmail.com')
                                ->subject('Nueva reserva de rifa');
                    }
                );

            } catch (\Exception $e) {

                \Log::error('Error enviando notificación de reserva: ' . $e->getMessage());

            }

  

            /*
            | Volvemos a cargar los números.
            */
            $numerosActualizados = RifaNumero::where('rifa_id', $request->rifa_id)
                ->orderBy('numero', 'asc')
                ->get([
                    'id',
                    'rifa_id',
                    'numero',
                    'estado',
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Los números fueron reservados correctamente.',
                'numeros' => $numerosActualizados
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'No fue posible realizar la reserva.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
