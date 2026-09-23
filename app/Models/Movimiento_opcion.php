<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento_opcion extends Model
{
    use HasFactory;
    protected $table = "movimiento_opcion";
    protected $fillable = [
        'id',
        'opcion',
        'tipo',
        'id_user'
    ];
}
