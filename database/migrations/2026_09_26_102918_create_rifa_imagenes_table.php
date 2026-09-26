<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rifa_imagenes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rifa_id')
                ->constrained('rifas')
                ->onDelete('cascade');

            $table->string('imagen');

            $table->integer('orden')->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rifa_imagenes');
    }
};