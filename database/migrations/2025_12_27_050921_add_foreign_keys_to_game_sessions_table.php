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
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->foreign(['host_player_id'], 'game_sessions_ibfk_1')->references(['PlayerId'])->on('players')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['current_player_id'], 'game_sessions_ibfk_2')->references(['PlayerId'])->on('players')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->dropForeign('game_sessions_ibfk_1');
            $table->dropForeign('game_sessions_ibfk_2');
        });
    }
};
