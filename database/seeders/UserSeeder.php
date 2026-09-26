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
        $user->token = 'qw1233eedfc';
        $user->id_user_padre = null;
        $user->save();


        $user2 = new User();
        $user2->firts_name = "Matias";
        $user2->last_name = "Herazo";
        $user2->email = 'matias@innovacion.analitica.co';
        $user2->username = 'herazo1';
        $user2->img = 'none.png';
        $user2->rol_id = 2;
        $user2->token = 'wsdespolk98';
        $user2->id_user_padre = 1;
        $user2->password = bcrypt('123423');
        $user2->save();

        $user3 = new User();
        $user3->firts_name = "Juan";
        $user3->last_name = "Herazo";
        $user3->email = 'juan@innovacion.analitica.co';
        $user3->username = 'herazo2';
        $user3->img = 'none.png';
        $user3->rol_id = 2;
        $user3->token = 'wsd902olk98';
        $user3->id_user_padre = 1;
        $user3->password = bcrypt('123456');
        $user3->save();

        $user4 = new User();
        $user4->firts_name = "Daniel";
        $user4->last_name = "Herazo";
        $user4->email = 'daniel@innovacion.analitica.co';
        $user4->username = 'herazo3';
        $user4->img = 'none.png';
        $user4->rol_id = 2;
        $user4->token = 'wsdespolssk98s';
        $user4->id_user_padre = 1;
        $user4->password = bcrypt('23412');
        $user4->save();

        $user5 = new User();
        $user5->firts_name = "Carlos";
        $user5->last_name = "Herazo";
        $user5->email = 'juaon@innovacion.analitica.co';
        $user5->username = 'herazo4';
        $user5->img = 'none.png';
        $user5->rol_id = 2;
        $user5->token = 'wsdxzxzkkl98';
        $user5->id_user_padre = 1;
        $user5->password = bcrypt('988009');
        $user5->save();

        $user6 = new User();
        $user6->firts_name = "Andres";
        $user6->last_name = "Herazo";
        $user6->email = 'Andres@innovacion.analitica.co';
        $user6->username = 'herazo5';
        $user6->img = 'none.png';
        $user6->rol_id = 2;
        $user6->token = 'ws98sasasbcha';
        $user6->id_user_padre = 1;
        $user6->password = bcrypt('988009');
        $user6->save();
    }
}
