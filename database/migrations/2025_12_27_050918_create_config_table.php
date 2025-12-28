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
        Schema::create('config', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('minPlayers')->default(2);
            $table->integer('maxPlayers')->default(5);
            $table->integer('max_turns')->nullable()->default(50);
            $table->integer('version')->default(1);
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config');
    }
};
