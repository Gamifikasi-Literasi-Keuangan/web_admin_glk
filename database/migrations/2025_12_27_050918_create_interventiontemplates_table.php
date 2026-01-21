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
        Schema::dropIfExists('interventiontemplates');

        Schema::create('interventiontemplates', function (Blueprint $table) {
            $table->id();
            $table->integer('level')->index();
            $table->string('risk_level')->nullable();
            $table->string('category')->nullable();
            $table->text('title_template');
            $table->text('message_template');
            $table->json('actions_template')->nullable();
            $table->text('heed_message')->nullable();
            $table->boolean('is_mandatory')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
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
