<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilingQuestion extends Model
{
    protected $fillable = [
        'question_code',
        'question_text',
        'max_score',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_score' => 'integer'
    ];

    /**
     * Get options for this question
     */
    public function options()
    {
        return $this->hasMany(ProfilingQuestionOption::class, 'question_id');
    }

    /**
     * Get financial aspects associated with this question
     */
    public function aspects()
    {
        return $this->belongsToMany(
            FinancialAspect::class,
            'profiling_question_aspects',
            'question_id',
            'aspect_id'
        );
    }

    /**
     * Scope to get only active questions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get question with options and aspects
     */
    public function scopeWithRelations($query)
    {
        return $query->with(['options', 'aspects']);
    }
}
