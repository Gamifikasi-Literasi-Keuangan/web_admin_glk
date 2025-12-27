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
        Schema::table('telemetry', function (Blueprint $table) {
            $table->foreign(['sessionId'], 'telemetry_ibfk_1')->references(['sessionId'])->on('game_sessions')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['playerId'], 'telemetry_ibfk_2')->references(['PlayerId'])->on('players')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('telemetry', function (Blueprint $table) {
            $table->dropForeign('telemetry_ibfk_1');
            $table->dropForeign('telemetry_ibfk_2');
        });
    }
};
