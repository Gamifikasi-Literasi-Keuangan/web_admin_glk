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
        Schema::create('training_datasets', function (Blueprint $table) {
            $table->id();
            $table->string('pendapatan', 50);
            $table->string('anggaran', 50);
            $table->string('tabungan_dan_dana_darurat', 50);
            $table->string('utang', 50);
            $table->string('investasi', 50);
            $table->string('asuransi_dan_proteksi', 50);
            $table->string('tujuan_jangka_panjang', 50);
            $table->string('cluster', 50); // Financial Novice, Explorer, etc
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index('cluster');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_datasets');
    }
};
