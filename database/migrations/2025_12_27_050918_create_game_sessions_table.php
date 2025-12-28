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
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->string('sessionId', 50)->primary();
            $table->string('host_player_id', 50)->index('game_sessions_ibfk_1');
            $table->integer('max_players')->nullable()->default(5);
            $table->integer('max_turns')->nullable()->default(100);
            $table->string('status', 20);
            $table->string('current_player_id', 50)->nullable()->index('game_sessions_ibfk_2');
            $table->integer('current_turn')->nullable()->default(0);
            $table->longText('game_state')->nullable();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};
