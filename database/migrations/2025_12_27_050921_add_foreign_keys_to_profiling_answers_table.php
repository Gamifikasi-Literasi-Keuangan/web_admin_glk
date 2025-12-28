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
        Schema::table('profiling_answers', function (Blueprint $table) {
            $table->foreign(['player_id'], 'profiling_answers_ibfk_1')->references(['PlayerId'])->on('players')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiling_answers', function (Blueprint $table) {
            $table->dropForeign('profiling_answers_ibfk_1');
        });
    }
};
