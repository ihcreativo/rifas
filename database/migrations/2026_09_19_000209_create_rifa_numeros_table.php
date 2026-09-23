<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rifa_numeros', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rifa_id')
                ->constrained('rifas')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('numero');

            $table->enum('estado', [
                'disponible',
                'reservado',
                'pagado'
            ])->default('disponible');

            $table->string('nombre')->nullable();

            $table->string('whatsapp')->nullable();

            $table->dateTime('fecha_reserva')->nullable();

            $table->dateTime('fecha_pago')->nullable();

            $table->timestamps();

            // No permite repetir el mismo número
            // dentro de una misma rifa.
            $table->unique(['rifa_id', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rifa_numeros');
    }
};