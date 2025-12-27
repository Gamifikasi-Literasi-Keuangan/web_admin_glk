<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingDataset extends Model
{
    protected $fillable = [
        'pendapatan',
        'anggaran',
        'tabungan_dan_dana_darurat',
        'utang',
        'investasi',
        'asuransi_dan_proteksi',
        'tujuan_jangka_panjang',
        'cluster',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Scope untuk data aktif saja
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope berdasarkan cluster
     */
    public function scopeByCluster($query, $cluster)
    {
        return $query->where('cluster', $cluster);
    }

    /**
     * Get distribusi data per cluster
     */
    public static function getDistribution()
    {
        return self::active()
            ->selectRaw('cluster, COUNT(*) as count')
            ->groupBy('cluster')
            ->get()
            ->pluck('count', 'cluster');
    }
}
