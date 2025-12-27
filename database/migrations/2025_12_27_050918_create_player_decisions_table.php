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
        Schema::create('player_decisions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('player_id', 50)->index('player_decisions_ibfk_1');
            $table->string('session_id', 50)->index('player_decisions_ibfk_2');
            $table->string('turn_id', 50)->nullable()->index('player_decisions_ibfk_3');
            $table->integer('turn_number')->nullable();
            $table->string('content_id', 50);
            $table->string('content_type', 50);
            $table->char('selected_option', 1)->nullable();
            $table->boolean('is_correct');
            $table->integer('score_change');
            $table->integer('decision_time_seconds')->nullable();
            $table->boolean('intervention_triggered')->nullable()->default(false);
            $table->integer('intervention_level')->nullable();
            $table->longText('behavioral_signals')->nullable();
            $table->string('intervention_id', 100)->nullable();
            $table->string('intervention_type', 100)->nullable();
            $table->string('player_response', 50)->nullable();
            $table->string('actual_decisions', 50)->nullable();
            $table->longText('vector_representation')->nullable();
            $table->float('similarity_score')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_decisions');
    }
};
