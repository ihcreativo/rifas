<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asistencia_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            $table->string('titulo');
            $table->datetime('fecha_inicio');
            $table->datetime('fecha_fin');
            $table->enum('estado',['on','off']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokens_asistencia');
    }
};
