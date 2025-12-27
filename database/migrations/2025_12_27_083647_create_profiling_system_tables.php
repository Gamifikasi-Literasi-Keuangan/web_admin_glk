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
        // Table 1: Financial Aspects (Static data)
        Schema::create('financial_aspects', function (Blueprint $table) {
            $table->id();
            $table->string('aspect_key', 100)->unique();
            $table->string('display_name', 150);
            $table->timestamps();
        });

        // Table 2: Profiling Questions
        Schema::create('profiling_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_code', 50)->unique();
            $table->text('question_text');
            $table->integer('max_score');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Table 3: Profiling Question Options
        Schema::create('profiling_question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('profiling_questions')->onDelete('cascade');
            $table->string('option_code', 5);
            $table->string('option_token', 64)->unique;
            $table->text('option_text');
            $table->integer('score');
            $table->timestamps();

            $table->index('question_id');
        });

        // Table 4: Profiling Question Aspects (Many-to-Many relationship)
        Schema::create('profiling_question_aspects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('profiling_questions')->onDelete('cascade');
            $table->foreignId('aspect_id')->constrained('financial_aspects')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['question_id', 'aspect_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiling_question_aspects');
        Schema::dropIfExists('profiling_question_options');
        Schema::dropIfExists('profiling_questions');
        Schema::dropIfExists('financial_aspects');
    }
};
