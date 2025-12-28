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
        Schema::create('ai_feedback_loop', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('player_id', 50)->nullable();
            $table->string('risk_level', 20)->nullable();
            $table->string('trigger_type', 50)->nullable();
            $table->float('threshold_value')->nullable();
            $table->integer('intervention_template_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_feedback_loop');
    }
};
