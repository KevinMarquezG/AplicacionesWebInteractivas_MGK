<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->enum('status', ['draft', 'pending_approval', 'published', 'rejected'])
                ->default('pending_approval')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->enum('status', ['draft', 'pending_approval', 'published', 'rejected'])
                ->default('published')
                ->change();
        });
    }
};
