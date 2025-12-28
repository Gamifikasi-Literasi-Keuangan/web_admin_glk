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
        Schema::table('player_decisions', function (Blueprint $table) {
            $table->foreign(['player_id'], 'player_decisions_ibfk_1')->references(['PlayerId'])->on('players')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['session_id'], 'player_decisions_ibfk_2')->references(['sessionId'])->on('game_sessions')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['turn_id'], 'player_decisions_ibfk_3')->references(['turn_id'])->on('turns')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('player_decisions', function (Blueprint $table) {
            $table->dropForeign('player_decisions_ibfk_1');
            $table->dropForeign('player_decisions_ibfk_2');
            $table->dropForeign('player_decisions_ibfk_3');
        });
    }
};
