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
        Schema::create('recommendations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('player_id', 50)->nullable();
            $table->text('recommendation')->nullable();
            $table->string('category', 50)->nullable();
            $table->longText('reason')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->longText('peer_insight')->nullable();
            $table->longText('components')->nullable();
            $table->longText('path_steps')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
