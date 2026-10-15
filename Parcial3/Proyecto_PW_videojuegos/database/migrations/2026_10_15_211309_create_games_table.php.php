<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('developer_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 8, 2)->default(0.00);
            $table->string('cover_image')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('executable_name')->nullable();
            $table->string('download_url')->nullable();
            $table->string('version')->default('1.0.0');
            $table->enum('status', ['draft', 'pending_approval', 'published', 'rejected'])->default('published');
            $table->date('release_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
