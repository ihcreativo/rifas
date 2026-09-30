<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RifaImagen;

class Rifa extends Model
{
    use HasFactory;

    protected $table = 'rifas';

    protected $fillable = [
        'nombre',
        'token',
        'id_user',
        'descripcion',
        'premio',
        'valor_opcion',
        'cantidad_numeros',
        'fecha_sorteo',
        'validacion_sorteo',
        'estado',
        'terminos_condiciones',
        'participantes' //numero de vendedores
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
    public function imagenes()
    {
        return $this->hasMany(RifaImagen::class, 'rifa_id')
            ->orderBy('orden');
    }
}