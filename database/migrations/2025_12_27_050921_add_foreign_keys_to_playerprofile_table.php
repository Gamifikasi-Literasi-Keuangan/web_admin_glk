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
        Schema::table('playerprofile', function (Blueprint $table) {
            $table->foreign(['PlayerId'], 'playerprofile_ibfk_1')->references(['PlayerId'])->on('players')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('playerprofile', function (Blueprint $table) {
            $table->dropForeign('playerprofile_ibfk_1');
        });
    }
};
