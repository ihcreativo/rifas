<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rifas', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->text('descripcion')->nullable();

            $table->string('premio');

            $table->decimal('valor_opcion', 12, 2);

            $table->unsignedInteger('cantidad_numeros')->default(100);

            $table->dateTime('fecha_sorteo')->nullable();
            $table->string('validacion_sorteo');

            $table->enum('estado', [
                'activa',
                'finalizada',
                'cancelada'
            ])->default('activa');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rifas');
    }
};