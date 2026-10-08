<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('torneos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('juego_deporte');
            $table->dateTime('fecha_evento');
            $table->unsignedInteger('cupo')->default(16);
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true); // true = abierto, false = cerrado por admin
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('torneos');
    }
};
