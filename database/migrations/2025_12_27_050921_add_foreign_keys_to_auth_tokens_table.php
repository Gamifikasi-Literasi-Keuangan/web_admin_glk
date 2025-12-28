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
        Schema::table('auth_tokens', function (Blueprint $table) {
            $table->foreign(['userId'], 'auth_tokens_ibfk_1')->references(['id'])->on('auth_users')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auth_tokens', function (Blueprint $table) {
            $table->dropForeign('auth_tokens_ibfk_1');
        });
    }
};
