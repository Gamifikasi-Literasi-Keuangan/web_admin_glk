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
        Schema::create('auth_tokens', function (Blueprint $table) {
            $table->string('token', 100)->primary();
            $table->enum('type', ['access', 'refresh'])->nullable()->default('access');
            $table->integer('userId')->index('auth_tokens_ibfk_1');
            $table->timestamp('expiresAt')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_tokens');
    }
};
