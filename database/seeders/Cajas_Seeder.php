<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cajas;

class Cajas_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rol = new Cajas();
        $rol->caja =  'Billetera';
        $rol->id_user =  1;
        $rol->save();

        $rol1 = new Cajas();
        $rol1->caja =  'Nequi';
        $rol1->id_user =  1;
        $rol1->save(); 
        
        $rol2 = new Cajas();
        $rol2->caja =  'Bancolombia';
        $rol2->id_user =  1;
        $rol2->save(); 
        
        $rol3 = new Cajas();
        $rol3->caja =  'Banco-IH';
        $rol3->id_user =  1;
        $rol3->save(); 
    }
}
