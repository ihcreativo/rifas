<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salida_opcion extends Model
{
    use HasFactory;
    protected $table = "salida_opcion";
    protected $fillable = [
        'id',
        'opcion',
        'id_user'
    ];
}
