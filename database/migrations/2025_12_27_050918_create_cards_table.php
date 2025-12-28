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
        Schema::create('cards', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('type', 10);
            $table->string('title', 200);
            $table->text('narration');
            $table->integer('scoreChange');
            $table->string('action', 50);
            $table->longText('categories');
            $table->longText('tags')->nullable();
            $table->integer('difficulty')->nullable();
            $table->integer('expected_benefit')->nullable()->default(0);
            $table->text('learning_objective')->nullable();
            $table->longText('weak_area_relevance')->nullable();
            $table->longText('cluster_relevance')->nullable();
            $table->float('historical_success_rate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
