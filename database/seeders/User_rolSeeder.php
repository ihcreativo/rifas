<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User_rol;

class User_rolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rol = new User_rol();
        $rol->nivel =  1;
        $rol->rol =  "Super Administrador";
        $rol->save();

        $rol1 = new User_rol();
        $rol1->nivel =  2;
        $rol1->rol =  "Administrador";
        $rol1->save();

        $rol2 = new User_rol();
        $rol2->nivel =  3;
        $rol2->rol =  "Cliente";
        $rol2->save();  
    }
}
