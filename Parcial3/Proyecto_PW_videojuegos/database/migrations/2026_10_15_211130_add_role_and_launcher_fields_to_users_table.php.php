<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->after('id')->constrained('roles')->onDelete('restrict');
            $table->string('avatar')->nullable()->after('email');
            $table->decimal('wallet_balance', 10, 2)->default(0.00)->after('password');
            $table->boolean('is_active')->default(true)->after('wallet_balance');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'avatar', 'wallet_balance', 'is_active']);
        });
    }
};
