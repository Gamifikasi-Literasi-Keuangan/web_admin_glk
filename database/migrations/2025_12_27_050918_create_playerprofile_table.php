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
        Schema::create('playerprofile', function (Blueprint $table) {
            $table->string('PlayerId', 50)->primary();
            $table->longText('onboarding_answers')->nullable();
            $table->string('cluster', 50)->nullable();
            $table->string('level', 20)->nullable();
            $table->longText('traits')->nullable();
            $table->longText('weak_areas')->nullable();
            $table->string('recommended_focus', 100)->nullable();
            $table->longText('lifetime_scores')->nullable();
            $table->longText('decision_history')->nullable();
            $table->longText('behavior_pattern')->nullable();
            $table->float('confidence_level')->nullable()->default(0.3);
            $table->longText('fuzzy_scores')->nullable();
            $table->longText('ann_probabilities')->nullable();
            $table->text('last_recommendation')->nullable();
            $table->timestamp('last_updated')->useCurrent();
            $table->longText('thresholds')->nullable();
            $table->text('last_threshold_update_reason')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playerprofile');
    }
};
