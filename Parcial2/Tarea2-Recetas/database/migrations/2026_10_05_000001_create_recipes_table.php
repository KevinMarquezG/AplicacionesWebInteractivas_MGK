<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->enum('category', ['desayuno', 'almuerzo', 'cena', 'postre', 'bebida']);
            $table->unsignedInteger('cooking_time'); // En minutos (> 0)
            $table->enum('difficulty', ['baja', 'media', 'alta']);
            $table->text('ingredients'); // Un ingrediente por línea
            $table->text('steps');       // Un paso por línea
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};