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
        Schema::table('participatesin', function (Blueprint $table) {
            $table->timestamp('last_ping_at')->nullable()->after('connection_status');
            $table->timestamp('last_break_end_at')->nullable()->after('last_ping_at');
            $table->boolean('on_break')->default(false)->after('is_ready');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participatesin', function (Blueprint $table) {
            $table->dropColumn(['last_ping_at', 'last_break_end_at', 'on_break']);
        });
    }
};
