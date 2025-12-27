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
        Schema::table('quiz_options', function (Blueprint $table) {
            $table->foreign(['quizId'], 'quiz_options_ibfk_1')->references(['id'])->on('quiz_cards')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_options', function (Blueprint $table) {
            $table->dropForeign('quiz_options_ibfk_1');
        });
    }
};
