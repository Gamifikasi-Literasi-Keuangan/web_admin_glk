<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'quiz_cards';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'id',
        'question',
        'correctOption',
        'correctScore',
        'incorrectScore',
        'tags',
        'difficulty',
        'learning_objective',
        'weak_area_relevance',
        'cluster_relevance',
        'historical_success_rate'
    ];

    protected $casts = [
        'tags' => 'array',
        'weak_area_relevance' => 'array',
        'cluster_relevance' => 'array',
        'historical_success_rate' => 'float',
        'correctScore' => 'integer',
        'incorrectScore' => 'integer',
        'difficulty' => 'integer',
    ];

    /**
     * Relasi ke Opsi Jawaban
     */
    public function options()
    {
        // Parameter ke-2: foreign_key di table anak (quizId)
        // Parameter ke-3: local_key di table induk (id)
        return $this->hasMany(QuizOption::class, 'quizId', 'id');
    }
}