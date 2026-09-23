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
        Schema::create('asistencia', function (Blueprint $table) {
            $table->id();
            $table->string('cedula');
            $table->string('bootcamp');
            $table->string('apellidos');
            $table->string('nombres');
            $table->unsignedBigInteger('id_token');
            $table->string('tokeuser');
            
            $table->foreign("id_token")
            ->references("id")
            ->on("asistencia_tokens")
            ->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencia');
    }
};
