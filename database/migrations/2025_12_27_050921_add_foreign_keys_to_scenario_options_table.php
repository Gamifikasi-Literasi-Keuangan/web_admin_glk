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
        Schema::table('scenario_options', function (Blueprint $table) {
            $table->foreign(['scenarioId'], 'scenario_options_ibfk_1')->references(['id'])->on('scenarios')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scenario_options', function (Blueprint $table) {
            $table->dropForeign('scenario_options_ibfk_1');
        });
    }
};
