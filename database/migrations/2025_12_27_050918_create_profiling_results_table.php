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
        Schema::create('profiling_results', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('player_id', 50)->nullable();
            $table->longText('fuzzy_output')->nullable();
            $table->longText('ann_output')->nullable();
            $table->string('final_class', 50)->nullable();
            $table->longText('recommended_focus')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiling_results');
    }
};
