<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rifa;
use App\Models\RifaNumero;

class RifaSeeder extends Seeder
{
    public function run(): void
    {
        $rifa = Rifa::create([
            'id_user' => 1,
            'token' => 'MHi3490LOCRTLIO9',
            'nombre' => 'Rifa Garmin Forerunner 55',
            'descripcion' => 'Rifa de un Garmin Forerunner 55',
            'premio' => 'Garmin Forerunner 55',
            'valor_opcion' => 12000,
            'cantidad_numeros' => 100,
            'fecha_sorteo' => '10/10/2026',
            'validacion_sorteo' => 'Sinuano noche',
            'estado' => 'activa',
            'participantes' => 1
        ]);

        for ($i = 0; $i <= 99; $i++) {
            RifaNumero::create([
                'rifa_id' => $rifa->id,
                'numero' => $i,
                'id_vendedor' => '1',
                'estado' => 'disponible',
            ]);
        }
    }
}