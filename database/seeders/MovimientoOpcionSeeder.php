<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Movimiento_opcion;

class MovimientoOpcionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $opc = new Movimiento_opcion();
        $opc->opcion =  'Salario';
        $opc->tipo =  'in';
        $opc->id_user =  1;
        $opc->save();

        $opc2 = new Movimiento_opcion();
        $opc2->opcion =  'Ingreso Ocasional';
        $opc2->tipo =  'in';
        $opc2->id_user =  1;
        $opc2->save();

        $opc3 = new Movimiento_opcion();
        $opc3->opcion =  'Venta de Activo';
        $opc3->tipo =  'in';
        $opc3->id_user =  1;
        $opc3->save();

        $opc1 = new Movimiento_opcion();
        $opc1->opcion =  'Servicio publico';
        $opc1->tipo =  'out';
        $opc1->id_user =  1;
        $opc1->save();

        $opc4 = new Movimiento_opcion();
        $opc4->opcion =  'Mercado';
        $opc4->tipo =  'out';
        $opc4->id_user =  1;
        $opc4->save();

        $opc5 = new Movimiento_opcion();
        $opc5->opcion =  'Comida rapida';
        $opc5->tipo =  'out';
        $opc5->id_user =  1;
        $opc5->save();

        
    }
}
