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
        Schema::create('scenario_options', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('scenarioId', 50)->index('scenario_options_ibfk_1');
            $table->char('optionId', 1);
            $table->text('text');
            $table->longText('scoreChange');
            $table->text('response');
            $table->boolean('is_correct')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scenario_options');
    }
};
