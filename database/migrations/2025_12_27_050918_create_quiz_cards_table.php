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
        Schema::create('quiz_cards', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->text('question');
            $table->char('correctOption', 1);
            $table->integer('correctScore');
            $table->integer('incorrectScore');
            $table->longText('tags')->nullable();
            $table->integer('difficulty')->nullable();
            $table->text('learning_objective')->nullable();
            $table->longText('weak_area_relevance')->nullable();
            $table->longText('cluster_relevance')->nullable();
            $table->float('historical_success_rate')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_cards');
    }
};
