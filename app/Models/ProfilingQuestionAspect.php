<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilingQuestionAspect extends Model
{
    protected $fillable = [
        'question_id',
        'aspect_id'
    ];

    /**
     * Get the question
     */
    public function question()
    {
        return $this->belongsTo(ProfilingQuestion::class, 'question_id');
    }

    /**
     * Get the aspect
     */
    public function aspect()
    {
        return $this->belongsTo(FinancialAspect::class, 'aspect_id');
    }
}
