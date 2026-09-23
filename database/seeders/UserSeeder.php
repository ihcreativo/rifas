<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->firts_name = "Isaias";
        $user->last_name = "Herazo";
        $user->email = 'isaiasherazo@gmail.com';
        $user->username = 'MatyMary';
        $user->img = 'none.png';
        $user->password = bcrypt('123123');
        $user->rol_id = 1;
        $user->save();

        // $user1 = new User();
        // $user1->firts_name = "Adrian";
        // $user1->last_name = "Carvajal";
        // $user1->email = 'adria@innovacion.analitica.co';
        // $user1->username = 'admin';
        // $user1->img = 'none.png';
        // $user1->password = bcrypt('admin1235');
        // $user1->save();

        $user2 = new User();
        $user2->firts_name = "Matias";
        $user2->last_name = "Herazo";
        $user2->email = 'matias@innovacion.analitica.co';
        $user2->username = 'herazo';
        $user2->img = 'none.png';
        $user2->rol_id = 3;
        $user2->password = bcrypt('123123');
        $user2->save();
    }
}
