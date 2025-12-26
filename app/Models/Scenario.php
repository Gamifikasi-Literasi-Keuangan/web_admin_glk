<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scenario extends Model
{
    use SoftDeletes;

    protected $table = 'scenarios';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    // TAMBAHKAN KOLOM BARU DI SINI
    protected $fillable = [
        'id',
        'type',
        'category',
        'title',
        'question',
        'tags',
        'difficulty',
        'expected_benefit',
        'learning_objective',
        'weak_area_relevance',
        'cluster_relevance',
        'historical_success_rate'
    ];

    protected $casts = [
        'tags' => 'array',
        'weak_area_relevance' => 'array', // <--- Auto JSON encode/decode
        'cluster_relevance' => 'array',   // <--- Auto JSON encode/decode
        'historical_success_rate' => 'float'
    ];

    public function options()
    {
        return $this->hasMany(ScenarioOption::class, 'scenarioId', 'id');
    }
}