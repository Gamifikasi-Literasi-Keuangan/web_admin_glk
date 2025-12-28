<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialAspect extends Model
{
    protected $fillable = [
        'aspect_key',
        'display_name'
    ];

    /**
     * Get questions associated with this aspect
     */
    public function questions()
    {
        return $this->belongsToMany(
            ProfilingQuestion::class,
            'profiling_question_aspects',
            'aspect_id',
            'question_id'
        );
    }

    /**
     * Get all aspect keys as array
     */
    public static function getAllKeys()
    {
        return self::pluck('aspect_key', 'id')->toArray();
    }
}
