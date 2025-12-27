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
        Schema::create('auth_users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('username', 50)->nullable();
            $table->string('google_id')->nullable()->unique('google_id');
            $table->text('passwordHash')->nullable();
            $table->string('role', 20)->nullable()->default('player');
            $table->text('avatar')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_users');
    }
};
