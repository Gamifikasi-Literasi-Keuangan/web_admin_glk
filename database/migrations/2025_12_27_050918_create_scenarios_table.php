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
        Schema::create('scenarios', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('category', 100);
            $table->string('title', 200)->nullable();
            $table->text('question');
            $table->longText('tags')->nullable();
            $table->integer('difficulty')->nullable();
            $table->integer('expected_benefit')->nullable()->default(0);
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
        Schema::dropIfExists('scenarios');
    }
};
