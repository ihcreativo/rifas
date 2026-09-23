<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    use HasFactory;

    protected $fillable = [
        'salida',
        'detalle',
        'id_user',
        'id_opcion',
        'id_caja',
        'fecha',
        'hora',
        'id_user'
    ];
}
