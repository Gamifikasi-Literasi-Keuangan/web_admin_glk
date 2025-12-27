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
        Schema::create('turns', function (Blueprint $table) {
            $table->string('turn_id', 50)->primary();
            $table->string('session_id', 50)->index('turns_ibfk_1');
            $table->string('player_id', 50)->index('turns_ibfk_2');
            $table->integer('turn_number');
            $table->timestamp('ended_at')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('started_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turns');
    }
};
