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
        Schema::table('turns', function (Blueprint $table) {
            $table->foreign(['session_id'], 'turns_ibfk_1')->references(['sessionId'])->on('game_sessions')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['player_id'], 'turns_ibfk_2')->references(['PlayerId'])->on('players')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turns', function (Blueprint $table) {
            $table->dropForeign('turns_ibfk_1');
            $table->dropForeign('turns_ibfk_2');
        });
    }
};
