<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rifa;
use App\Models\RifaNumero;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\RifaImagen;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RifaController extends Controller
{

    // public function index(Request $request)
    // {
    //     $query = RifaNumero::where('rifa_id', $request->rifa_id);

    //     // Si viene vendedor_id, mostrar únicamente
    //     // los números asignados a ese vendedor
    //     if (isset($request->vendedor_id)) {
    //         $query->where('id_vendedor', $request->vendedor_id);
    //     }

    //     $numeros = $query
    //         ->orderBy('numero', 'asc')
    //         ->get([
    //             'id',
    //             'rifa_id',
    //             'numero',
    //             'estado',
    //             'id_vendedor',
    //         ]);

    //     return response()->json([
    //         'success' => true,
    //         'numeros' => $numeros
    //     ]);
    // }
    public function index(Request $request)
    {
        $query = RifaNumero::with('rifa')
            ->where('rifa_id', $request->rifa_id);

        // Si viene vendedor_id, mostrar únicamente
        // los números asignados a ese vendedor
        if (isset($request->vendedor_id)) {
            $query->where('id_vendedor', $request->vendedor_id);
        }

        $numeros = $query
            ->orderBy('numero', 'asc')
            ->get([
                'id',
                'rifa_id',
                'numero',
                'estado',
                'id_vendedor',
            ]);

        return response()->json([
            'success' => true,
            'rifa' => $numeros->first()?->rifa,
            'numeros' => $numeros
        ]);
    }

public function estadisticas($id)
{
    try {
        $rifa = Rifa::findOrFail($id);

        $numeros = RifaNumero::with('vendedor')
            ->where('rifa_id', $rifa->id)
            ->get();

        $total = $numeros->count();

        // ESTADÍSTICAS GENERALES
        $pagados = $numeros->where('estado', 'pagado')->count();

        $reservados = $numeros->where('estado', 'reservado')->count();

        $disponibles = $numeros->where('estado', 'disponible')->count();

        $porcentaje = function ($cantidad, $base) {
            return $base > 0
                ? round(($cantidad / $base) * 100, 2)
                : 0;
        };

        $valorNumero = (float) $rifa->valor_opcion;

        $general = [
            'total_numeros' => $total,
            'pagados' => $pagados,
            'reservados' => $reservados,
            'disponibles' => $disponibles,

            'porcentaje_pagados' => $porcentaje($pagados, $total),
            'porcentaje_reservados' => $porcentaje($reservados, $total),
            'porcentaje_disponibles' => $porcentaje($disponibles, $total),

            'porcentaje_ocupacion' => $porcentaje(
                $pagados + $reservados,
                $total
            ),

            'recaudo_confirmado' => $pagados * $valorNumero,

            'pendiente_potencial' => $reservados * $valorNumero,

            'valor_potencial_total' => $total * $valorNumero,
        ];

        // ESTADÍSTICAS POR VENDEDOR
        $vendedores = $numeros
            ->filter(function ($numero) {
                return !is_null($numero->id_vendedor);
            })
            ->groupBy('id_vendedor')
            ->map(function ($grupo) use ($valorNumero, $porcentaje) {

                $vendedor = $grupo->first()->vendedor;
                $totalAsignados = $grupo->count();

                $pagados = $grupo
                    ->where('estado', 'pagado')
                    ->count();

                $reservados = $grupo
                    ->where('estado', 'reservado')
                    ->count();

                $disponibles = $grupo
                    ->where('estado', 'disponible')
                    ->count();

                return [
                    'id' => $vendedor->id,
                    'nombre' => trim(
                        ($vendedor->firts_name ?? '') . ' ' .
                        ($vendedor->last_name ?? '')
                    ),
                    'total_asignados' => $totalAsignados,

                    'pagados' => $pagados,
                    'reservados' => $reservados,
                    'disponibles' => $disponibles,

                    'porcentaje_pagados' => $porcentaje(
                        $pagados,
                        $totalAsignados
                    ),

                    'porcentaje_reservados' => $porcentaje(
                        $reservados,
                        $totalAsignados
                    ),

                    'porcentaje_disponibles' => $porcentaje(
                        $disponibles,
                        $totalAsignados
                    ),

                    'porcentaje_ocupacion' => $porcentaje(
                        $pagados + $reservados,
                        $totalAsignados
                    ),

                    'recaudo_confirmado' => $pagados * $valorNumero,

                    'pendiente_potencial' => $reservados * $valorNumero,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'rifa' => [
                'id' => $rifa->id,
                'nombre' => $rifa->nombre,
                'valor_numero' => $valorNumero,
            ],
            'general' => $general,
            'vendedores' => $vendedores,
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => 'No fue posible consultar las estadísticas.',
        ], 500);
    }
}

    /**
     * Mostrar información de un número.
     */
    public function show($token)
    {
        $rifa = Rifa::where('token', $token)->firstOrFail();

        return view('rifa_client', ['id'=> $rifa->id, 'token' => $rifa->token]);

        // return view('rifa_client', ['id'=> $id]);  
    }


    public function showRifa($tr, $tv)
    {
        $rifa = Rifa::where('token', $tr)->firstOrFail();

        $vendedor = User::where('token', $tv)
            ->where('rol_id', 2)
            ->firstOrFail();

        $numeros = RifaNumero::where('rifa_id', $rifa->id)
            ->where('id_vendedor', $vendedor->id)
            ->orderBy('numero')
            ->get();

        if ($numeros->isEmpty()) {
            abort(404);
        }

        $imagenes = $rifa->imagenes()
            ->orderBy('orden')
            ->get()
            ->map(function ($imagen) {
                return [
                    'id' => $imagen->id,
                    'imagen' => asset('storage/' . $imagen->imagen),
                    'orden' => $imagen->orden,
                ];
            });
        $imagenCompartir = $imagenes->first()['imagen'] ?? asset('images/rifa-default.jpg');
        return view('rifa_client', [
            'id' => $rifa->id,
            'token' => $rifa->token,
            'vendedor' => $vendedor,
            'numeros' => $numeros,
            'idV' => $vendedor->id,
            'imagenes' => $imagenes,
            'imagenCompartir' => $imagenCompartir,
            'title' => $rifa->nombretoken,
        ]);
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
            $referenciaWompi = 'RIFA-' . strtoupper(Str::random(12));

            RifaNumero::whereIn('id', $request->numeros)
                ->where('rifa_id', $request->rifa_id)
                ->update([
                    'estado' => 'reservado',
                    'nombre' => $request->nombre_cliente,
                    'whatsapp' => $request->whatsapp_cliente,
                    'fecha_reserva' => now(),
                    'wompi_reference' => $referenciaWompi,
                    'estado_pago' => 'pendiente',
                ]);
            

            DB::commit();
            try {

                $numerosReservados = $numeros->pluck('numero')
                    ->map(function ($numero) {
                        return str_pad($numero, 2, '0', STR_PAD_LEFT);
                    })
                    ->implode(', ');

                // Obtener vendedor
                $vendedor = User::find($numeros->first()->id_vendedor);

                if (!$vendedor || !$vendedor->email) {
                    throw new \Exception('No fue posible encontrar el correo del vendedor.');
                }

                // Normalizar WhatsApp del cliente
                $whatsapp = preg_replace('/\D/', '', $request->whatsapp_cliente);

                // Si es un número colombiano de 10 dígitos
                if (strlen($whatsapp) === 10) {
                    $whatsapp = '57' . $whatsapp;
                }

                ///mensaje
                "💰 *Datos para realizar el pago:*\n" .
                "💳 Medio de pago: {$vendedor->tipo_pago}\n" .
                "📱 Número de pago: {$vendedor->numero_pago}\n\n" .

                "⚠️ Recuerda realizar el pago para confirmar tu participación.\n\n" .

                "Una vez realizado el pago, envía el comprobante por este medio.\n\n" .

                "¡Gracias por participar! 🍀";

                // Mensaje para WhatsApp
                $mensajeWhatsapp =
                    "Hola {$request->nombre_cliente}, 👋\n\n" .
                    "Hemos recibido tu reserva en la rifa.\n\n" .
                    "🎟️ Números reservados: {$numerosReservados}\n" .
                    "💰 Recuerda realizar el pago para confirmar tu participación.\n\n" .
                    "💰 *Datos para realizar el pago:*\n" .
                    "💳 Medio de pago: {$vendedor->tipo_pago}\n" .
                    "📱 Número de pago: {$vendedor->numero_pago}\n\n" .
                    "Una vez realizado el pago, envía el comprobante por este medio.\n\n" .
                    "¡Gracias por participar! 🍀";

                $linkWhatsapp = 'https://wa.me/' . $whatsapp . '?text=' . urlencode($mensajeWhatsapp);


                // Correo HTML
                Mail::html(
                    "
                    <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;'>

                        <h2 style='color:#198754;'>
                            🎟️ Nueva reserva de rifa
                        </h2>

                        <p><strong>Cliente:</strong> {$request->nombre_cliente}</p>

                        <p>
                            <strong>WhatsApp:</strong>
                            {$request->whatsapp_cliente}
                        </p>

                        <p>
                            <strong>Rifa:</strong>
                            {$request->rifa_id}
                        </p>

                        <p>
                            <strong>Números reservados:</strong>
                            {$numerosReservados}
                        </p>

                        <p>
                            <strong>Vendedor:</strong>
                            {$vendedor->firts_name} {$vendedor->last_name}
                        </p>

                        <p>
                            <strong>Fecha:</strong>
                            " . now()->format('d/m/Y H:i:s') . "
                        </p>

                        <hr>

                        <div style='text-align:center;margin:30px 0;'>

                            <a href='{$linkWhatsapp}'
                            target='_blank'
                            style='
                                display:inline-block;
                                background:#25D366;
                                color:#ffffff;
                                padding:14px 25px;
                                text-decoration:none;
                                border-radius:8px;
                                font-weight:bold;
                                font-size:16px;
                            '>
                                📱 Enviar WhatsApp al cliente
                            </a>

                        </div>

                        <p style='font-size:13px;color:#777;'>
                            Haz clic en el botón para abrir WhatsApp con el mensaje
                            de confirmación preparado.
                        </p>

                    </div>
                    ",
                    function ($message) use ($vendedor) {

                        // Correo principal: vendedor
                        $message->to($vendedor->email)

                                // Copia: administrador
                                ->cc('isaiasherazo@gmail.com')

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
                'referencia_wompi' => $referenciaWompi,
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


    public function saveRifa(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'premio' => 'required|string|max:255',
            'valor_opcion' => 'required|numeric|min:0',
            'cantidad_numeros' => 'required|integer|min:1',
            'fecha_sorteo' => 'required|date',
            'validacion_sorteo' => 'required|string|max:50',
            'terminos_condiciones' => 'nullable|string',
        ]);

        try {

            DB::beginTransaction();

            $rifa = Rifa::create([
                'nombre' => $request->nombre,
                'id_user' =>auth()->user()->id,
                'token' => Str::random(10),
                'descripcion' => $request->descripcion,
                'premio' => $request->premio,
                'valor_opcion' => $request->valor_opcion,
                'cantidad_numeros' => $request->cantidad_numeros,
                'fecha_sorteo' => $request->fecha_sorteo,
                'validacion_sorteo' => $request->validacion_sorteo,
                'participantes' => 1,
                'terminos_condiciones' => $request->terminos_condiciones
            ]);

            /*
            * Crear los números de la rifa
            */
            for ($i = 1; $i <= $request->cantidad_numeros; $i++) {

                RifaNumero::create([
                    'rifa_id' => $rifa->id,
                    'numero' => $i,
                    'estado' => 'disponible',
                    'id_vendedor' => null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rifa creada correctamente.',
                'rifa' => $rifa
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'No fue posible crear la rifa.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function actualizarRifa(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'premio' => 'required|string|max:255',
            'valor_opcion' => 'required|numeric|min:0',
            'fecha_sorteo' => 'required|date',
            'validacion_sorteo' => 'required|string|max:255',
            'terminos_condiciones' => 'nullable|string',
        ]);

        try {

            $rifa = Rifa::findOrFail($id);

            $rifa->nombre = $request->nombre;
            $rifa->descripcion = $request->descripcion;
            $rifa->premio = $request->premio;
            $rifa->valor_opcion = $request->valor_opcion;
            $rifa->fecha_sorteo = $request->fecha_sorteo;
            $rifa->validacion_sorteo = $request->validacion_sorteo;
            $rifa->terminos_condiciones = $request->terminos_condiciones;

            $rifa->save();

            return response()->json([
                'success' => true,
                'message' => 'La rifa fue actualizada correctamente.',
                'rifa' => $rifa
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'No fue posible actualizar la rifa.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function eliminarRifa($id)
    {
        try {

            DB::beginTransaction();

            $rifa = Rifa::findOrFail($id);

            // Verificar si existen números reservados o pagados
            $numerosNoDisponibles = RifaNumero::where('rifa_id', $rifa->id)
                ->whereIn('estado', ['reservado', 'pagado'])
                ->count();

            if ($numerosNoDisponibles > 0) {

                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar la rifa porque tiene números reservados o pagados.'
                ], 422);
            }

            // Eliminar imágenes físicas
            foreach ($rifa->imagenes as $imagen) {

                if (Storage::disk('public')->exists($imagen->imagen)) {
                    Storage::disk('public')->delete($imagen->imagen);
                }
            }

            // Eliminar registros de imágenes
            $rifa->imagenes()->delete();

            // Eliminar números
            RifaNumero::where('rifa_id', $rifa->id)->delete();

            // Finalmente eliminar la rifa
            $rifa->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'La rifa fue eliminada correctamente.'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getRifasByVendedor()
    {
        $idVendedor = auth()->id();

        $rifas = Rifa::whereHas('numeros', function ($query) use ($idVendedor) {
                $query->where('id_vendedor', $idVendedor);
            })
            ->with([
                'numeros' => function ($query) use ($idVendedor) {
                    $query->where('id_vendedor', $idVendedor)
                        ->orderBy('numero', 'asc');
                }
            ])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'rifas' => $rifas
        ]);
    }
    public function cambiarEstadoNumero(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:disponible,reservado,pagado',
        ]);

        try {

            $numero = RifaNumero::where('id', $id)
                ->where('id_vendedor', auth()->id())
                ->firstOrFail();

            $numero->estado = $request->estado;
            $numero->save();

            return response()->json([
                'success' => true,
                'message' => 'El estado del número fue actualizado correctamente.',
                'numero' => [
                    'id' => $numero->id,
                    'numero' => $numero->numero,
                    'estado' => $numero->estado,
                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'No fue posible actualizar el estado.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getRifasByUser()
    {
        $rifas = Rifa::where('id_user', auth()->id())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'rifas' => $rifas
        ]);
    }

    public function repartirNumeros(Request $request)
    {
        $request->validate([
            'id_rifa' => 'required|integer|exists:rifas,id',
            'vendedores' => 'required|array|min:1',
            'vendedores.*' => 'required|integer|exists:users,id',
        ]);

        try {

            DB::beginTransaction();

            // Rifa
            $rifa = Rifa::findOrFail($request->id_rifa);

            // Vendedores seleccionados
            $vendedores = User::whereIn('id', $request->vendedores)
                ->where('rol_id', 2)
                ->get();

            // Verificar que todos sean vendedores
            if ($vendedores->count() !== count($request->vendedores)) {
                throw new \Exception(
                    'Uno o más usuarios seleccionados no son vendedores válidos.'
                );
            }

            // Obtener números de la rifa
            $numeros = RifaNumero::where('rifa_id', $rifa->id)
                ->get();

            if ($numeros->isEmpty()) {
                throw new \Exception(
                    'La rifa no tiene números para repartir.'
                );
            }

             /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR CANTIDAD DE PARTICIPANTES
            |--------------------------------------------------------------------------
            */

            $rifa->participantes = $vendedores->count();
            $rifa->save();

            /*
            |--------------------------------------------------------------------------
            | MEZCLAR LOS NÚMEROS ALEATORIAMENTE
            |--------------------------------------------------------------------------
            */

            $numeros = $numeros->shuffle();

            $cantidadNumeros = $numeros->count();
            $cantidadVendedores = $vendedores->count();

            /*
            |--------------------------------------------------------------------------
            | CALCULAR DISTRIBUCIÓN
            |--------------------------------------------------------------------------
            */

            $cantidadBase = intdiv(
                $cantidadNumeros,
                $cantidadVendedores
            );

            $sobrantes = $cantidadNumeros % $cantidadVendedores;

            /*
            |--------------------------------------------------------------------------
            | ASIGNAR NÚMEROS
            |--------------------------------------------------------------------------
            */

            $indiceNumero = 0;

            foreach ($vendedores as $indiceVendedor => $vendedor) {

                // Cantidad que recibirá este vendedor
                $cantidadAsignar = $cantidadBase;

                // Los primeros vendedores reciben uno adicional
                if ($indiceVendedor < $sobrantes) {
                    $cantidadAsignar++;
                }

                for ($i = 0; $i < $cantidadAsignar; $i++) {

                    $numero = $numeros[$indiceNumero];

                    $numero->id_vendedor = $vendedor->id;

                    $numero->save();

                    $indiceNumero++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Los números fueron repartidos correctamente.',
                'rifa' => $rifa->id,
                'total_numeros' => $cantidadNumeros,
                'total_vendedores' => $cantidadVendedores,
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
    public function vendedoresConNumeros($id)
    {
        try {

            $rifa = Rifa::findOrFail($id);

            $numeros = RifaNumero::with('vendedor')
                ->where('rifa_id', $rifa->id)
                ->whereNotNull('id_vendedor')
                ->orderBy('id_vendedor')
                ->orderBy('numero')
                ->get();

            $vendedores = $numeros
                ->groupBy('id_vendedor')
                ->map(function ($numeros) {

                    $vendedor = $numeros->first()->vendedor;

                    return [
                        'id' => $vendedor->id,
                        'nombre' => $vendedor->firts_name . ' ' . $vendedor->last_name,
                        'email' => $vendedor->email,
                        'token' =>$vendedor->token,
                        'cantidad_numeros' => $numeros->count(),
                        // 'numeros' => $numeros->pluck('numero')->values(),
                        'numeros' => $numeros->map(function ($numero) {
                        return [
                            'id'=> $numero->id,
                            'numero' => $numero->numero,
                            'estado' => $numero->estado,
                            'nombre' => $numero->nombre,
                            'whatsapp' => $numero->whatsapp,
                        ];
                    })->values(),
                    ];
                })
                ->values();

            return response()->json([
                'success' => true,
                'rifa' => $rifa,
                'vendedores' => $vendedores
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    //imagenes
    public function subirImagenes(Request $request)
    {
        $request->validate([
            'rifa_id' => 'required|integer|exists:rifas,id',
            'imagenes' => 'required|array|min:1',
            'imagenes.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        try {
            $rifa = Rifa::findOrFail($request->rifa_id);
            $ordenActual = RifaImagen::where('rifa_id', $rifa->id)->max('orden');
            $ordenActual = $ordenActual ?? 0;
            $imagenesGuardadas = [];
            foreach ($request->file('imagenes') as $imagen) {
                $ordenActual++;
                $ruta = $imagen->store('rifas', 'public');
                $rifaImagen = RifaImagen::create([
                    'rifa_id' => $rifa->id,
                    'imagen' => $ruta,
                    'orden' => $ordenActual,
                ]);
                $imagenesGuardadas[] = $rifaImagen;
            }
            return response()->json([
                'success' => true,
                'message' => 'Las imágenes fueron subidas correctamente.',
                'imagenes' => $imagenesGuardadas,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible subir las imágenes.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function imagenes($id)
    {
        try {
            $rifa = Rifa::findOrFail($id);
            $imagenes = $rifa->imagenes()
                ->orderBy('orden')
                ->get()
                ->map(function ($imagen) {
                    return [
                        'id' => $imagen->id,
                        'imagen' => asset('storage/' . $imagen->imagen),
                        'orden' => $imagen->orden,
                    ];
                });

            return response()->json([
                'success' => true,
                'imagenes' => $imagenes,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function eliminarImagen($id)
    {
        try {

            $imagen = RifaImagen::findOrFail($id);

            // Eliminar archivo físico
            if ($imagen->imagen && Storage::disk('public')->exists($imagen->imagen)) {
                Storage::disk('public')->delete($imagen->imagen);
            }

            // Eliminar registro
            $imagen->delete();

            return response()->json([
                'success' => true,
                'message' => 'La imagen fue eliminada correctamente.'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'No fue posible eliminar la imagen.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function transferirNumeros(Request $request)
    {
        $datos = $request->validate([
            'id_rifa' => 'required|integer|exists:rifas,id',
            'vendedor_origen_id' => 'required|integer|different:vendedor_destino_id',
            'vendedor_destino_id' => 'required|integer|different:vendedor_origen_id',
            'numeros' => 'required|array|min:1',
            'numeros.*' => 'required|integer|distinct',
        ]);

        $destinoValido = User::where('id', $datos['vendedor_destino_id'])
            ->where('rol_id', 2)
            ->exists();

        if (!$destinoValido) {
            return response()->json([
                'success' => false,
                'message' => 'El vendedor de destino no es válido.'
            ], 422);
        }

        try {
            DB::transaction(function () use ($datos) {

                // Bloquear y comprobar que los números pertenecen
                // a la rifa y al vendedor de origen.
                $numeros = RifaNumero::where('rifa_id', $datos['id_rifa'])
                    ->where('id_vendedor', $datos['vendedor_origen_id'])
                    ->whereIn('id', $datos['numeros'])
                    ->lockForUpdate()
                    ->get();

                if ($numeros->count() !== count($datos['numeros'])) {
                    throw new \RuntimeException(
                        'Uno o más números no pertenecen al vendedor de origen o a esta rifa.'
                    );
                }

                // Solo cambia el vendedor. Se conservan el estado,
                // los datos del cliente y la información del pago.
                RifaNumero::where('rifa_id', $datos['id_rifa'])
                    ->where('id_vendedor', $datos['vendedor_origen_id'])
                    ->whereIn('id', $datos['numeros'])
                    ->update([
                        'id_vendedor' => $datos['vendedor_destino_id']
                    ]);

                Log::info('Transferencia manual de números de rifa', [
                    'rifa_id' => $datos['id_rifa'],
                    'vendedor_origen_id' => $datos['vendedor_origen_id'],
                    'vendedor_destino_id' => $datos['vendedor_destino_id'],
                    'numeros_ids' => $datos['numeros'],
                    'usuario_administrador_id' => auth()->id(),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Los números fueron transferidos correctamente.'
            ]);

        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);

        } catch (\Throwable $e) {
            Log::error('Error al transferir números de rifa', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al transferir los números.'
            ], 500);
        }
    }


    // public function datosConfirmacionWhatsApp($id)
    // {
    //     $numero = RifaNumero::findOrFail($id);

    //     abort_unless(
    //         (int) $numero->id_vendedor === (int) auth()->id(),
    //         403,
    //         'No tienes permiso para consultar este número.'
    //     );

    //     if ($numero->estado !== 'pagado') {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Este número todavía no está pagado.'
    //         ], 422);
    //     }

    //     if (empty($numero->whatsapp)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'El cliente no tiene un WhatsApp registrado.'
    //         ], 422);
    //     }

    //     $numeros = RifaNumero::where('rifa_id', $numero->rifa_id)
    //         ->where('whatsapp', $numero->whatsapp)
    //         ->where('estado', 'pagado')
    //         ->orderBy('numero')
    //         ->pluck('numero')
    //         ->map(fn ($n) => str_pad((string) $n, 2, '0', STR_PAD_LEFT))
    //         ->values();

    //     return response()->json([
    //         'success' => true,
    //         'nombre' => $numero->nombre,
    //         'whatsapp' => $numero->whatsapp,
    //         'numeros' => $numeros
    //     ]);
    // }
    public function datosConfirmacionWhatsApp($id)
    {
        $numero = RifaNumero::findOrFail($id);

        abort_unless(
            (int) $numero->id_vendedor === (int) auth()->id(),
            403,
            'No tienes permiso para consultar este número.'
        );

        if (!in_array($numero->estado, ['pagado', 'reservado'])) {
            return response()->json([
                'success' => false,
                'message' => 'El número debe estar reservado o pagado.'
            ], 422);
        }

        if (empty($numero->whatsapp)) {
            return response()->json([
                'success' => false,
                'message' => 'El cliente no tiene un WhatsApp registrado.'
            ], 422);
        }

        // Consultar los números del cliente en la misma rifa,
        // con el mismo vendedor y el mismo estado.
        $numerosCliente = RifaNumero::where('rifa_id', $numero->rifa_id)
            ->where('id_vendedor', $numero->id_vendedor)
            ->where('whatsapp', $numero->whatsapp)
            ->where('estado', $numero->estado)
            ->orderBy('numero')
            ->get();

        // Obtener la información de la rifa.
        $rifa = Rifa::findOrFail($numero->rifa_id);

        $valorNumero = (float) $rifa->valor_opcion;
        $cantidad = $numerosCliente->count();
        $total = $cantidad * $valorNumero;

        // Datos de pago del vendedor.
        $vendedor = User::find($numero->id_vendedor);

        $numeros = $numerosCliente->pluck('numero')
            ->map(fn ($n) => str_pad((string) $n, 2, '0', STR_PAD_LEFT))
            ->values();

        return response()->json([
            'success' => true,
            'estado' => $numero->estado,
            'nombre' => $numero->nombre,
            'whatsapp' => $numero->whatsapp,
            'numeros' => $numeros,
            'valor_numero' => $valorNumero,
            'cantidad' => $cantidad,
            'total' => $total,
            'tipo_pago' => $vendedor->tipo_pago ?? null,
            'numero_pago' => $vendedor->numero_pago ?? null,
        ]);
    }

}
