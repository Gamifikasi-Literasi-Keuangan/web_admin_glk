<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $table = 'cards';
    protected $primaryKey = 'id';
    public $incrementing = false; // Karena ID berupa String (VARCHAR)
    protected $keyType = 'string';

    // Kita hanya punya created_at dan deleted_at, tidak ada updated_at
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'id',
        'type', // risk / chance
        'title',
        'narration',
        'scoreChange',
        'action',
        'categories',
        'tags',
        'difficulty',
        'learning_objective',
        'weak_area_relevance',
        'cluster_relevance',
        'historical_success_rate'
    ];

    protected $casts = [
        'categories' => 'array',
        'tags' => 'array',
        'weak_area_relevance' => 'array',
        'cluster_relevance' => 'array',
        'historical_success_rate' => 'float',
        'scoreChange' => 'integer',
        'difficulty' => 'integer',
    ];
    public function decisions()
    {
        return $this->hasMany(PlayerDecision::class, 'content_id', 'id');
    }
}