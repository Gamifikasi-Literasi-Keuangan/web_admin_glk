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
        Schema::create('interventiontemplates', function (Blueprint $table) {
            $table->integer('level')->primary();
            $table->string('risk_level', 20);
            $table->string('title_template', 200);
            $table->text('message_template');
            $table->longText('actions_template')->nullable();
            $table->boolean('is_mandatory')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventiontemplates');
    }
};
