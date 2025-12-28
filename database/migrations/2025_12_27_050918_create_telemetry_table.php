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
        Schema::create('telemetry', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('sessionId', 50)->index('telemetry_ibfk_1');
            $table->string('playerId', 50)->index('telemetry_ibfk_2');
            $table->string('turn_id', 50);
            $table->string('tile_id', 50);
            $table->string('action', 50);
            $table->text('details')->nullable();
            $table->longText('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telemetry');
    }
};
