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
        Schema::create('participatesin', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('sessionId', 50)->index('sessionid');
            $table->string('playerId', 50)->index('playerid');
            $table->integer('player_order')->nullable();
            $table->integer('position')->nullable()->default(0);
            $table->integer('score')->nullable()->default(0);
            $table->string('connection_status', 20)->nullable()->default('disconnected');
            $table->boolean('is_ready')->nullable()->default(false);
            $table->integer('rank')->nullable();
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participatesin');
    }
};
