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
        Schema::create('profiling_answers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('player_id', 50)->index('profiling_answers_ibfk_1');
            $table->integer('question_id');
            $table->string('answer', 10);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiling_answers');
    }
};
