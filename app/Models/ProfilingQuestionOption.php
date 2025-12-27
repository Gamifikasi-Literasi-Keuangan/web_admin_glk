<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilingQuestionOption extends Model
{
    protected $fillable = [
        'question_id',
        'option_code',
        'option_text',
        'score'
    ];

    protected $casts = [
        'score' => 'integer'
    ];

    /**
     * Get the question that owns this option
     */
    public function question()
    {
        return $this->belongsTo(ProfilingQuestion::class, 'question_id');
    }
}
