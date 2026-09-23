<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class asistencia_tokens extends Model
{
    use HasFactory;
    protected $table = "asistencia_tokens";
    protected $fillable = [
        'id',
        'token',
        'fecha_inicio',
        'fecha_fin',
        'titulo',
        'fecha_estado'
    ];
}
