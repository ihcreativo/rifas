<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rifa extends Model
{
    use HasFactory;

    protected $table = 'rifas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'premio',
        'valor_opcion',
        'cantidad_numeros',
        'fecha_sorteo',
        'validacion_sorteo',
        'estado',
    ];

    protected $casts = [
        'valor_opcion' => 'decimal:2',
        'fecha_sorteo' => 'datetime',
    ];

    /**
     * Números pertenecientes a la rifa
     */
    public function numeros()
    {
        return $this->hasMany(RifaNumero::class);
    }
}