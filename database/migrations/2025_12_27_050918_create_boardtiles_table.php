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
        Schema::create('boardtiles', function (Blueprint $table) {
            $table->string('tile_id', 10)->primary();
            $table->string('name', 100);
            $table->string('category', 50)->nullable();
            $table->string('type', 20);
            $table->longText('linked_content')->nullable();
            $table->integer('position_index')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boardtiles');
    }
};
