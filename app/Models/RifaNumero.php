<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RifaNumero extends Model
{
    use HasFactory;

    protected $table = 'rifa_numeros';

    protected $fillable = [
        'rifa_id',
        'numero',
        'estado',
        'nombre',
        'whatsapp',
        'fecha_reserva',
        'fecha_pago',
    ];

    protected $casts = [
        'fecha_reserva' => 'datetime',
        'fecha_pago' => 'datetime',
    ];

    /**
     * Rifa a la que pertenece el número
     */
    public function rifa()
    {
        return $this->belongsTo(Rifa::class);
    }
}