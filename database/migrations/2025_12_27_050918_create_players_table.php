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
        Schema::create('players', function (Blueprint $table) {
            $table->string('PlayerId', 50)->primary();
            $table->integer('user_id')->unique('user_id');
            $table->string('name', 100);
            $table->text('avatar_url')->nullable();
            $table->integer('character_id')->nullable()->default(1);
            $table->integer('gamesPlayed')->nullable()->default(0);
            $table->string('initial_platform', 20)->nullable();
            $table->string('locale', 10)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
