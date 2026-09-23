<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;
    protected $table = "movimiento";
    protected $fillable = [
        'movimiento',
        'detalle',
        'id_user',
        'id_opcion',
        'id_caja'
    ];

    public function opcion(){
        return $this->belongsTo('App\Models\Movimiento_opcion', 'id_opcion');
    }
}
