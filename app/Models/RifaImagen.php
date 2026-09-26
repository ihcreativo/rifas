<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RifaImagen extends Model
{
    use HasFactory;

    protected $table = 'rifa_imagenes';

    protected $fillable = [
        'rifa_id',
        'imagen',
        'orden',
    ];

    public function rifa()
    {
        return $this->belongsTo(Rifa::class);
    }

}