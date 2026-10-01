<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rifa_numeros', function (Blueprint $table) {

            $table->string('wompi_reference', 100)
                ->nullable()
                ->index();

            $table->string('estado_pago', 30)
                ->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('rifa_numeros', function (Blueprint $table) {

            $table->dropColumn([
                'wompi_reference',
                'estado_pago',
            ]);
        });
    }
};