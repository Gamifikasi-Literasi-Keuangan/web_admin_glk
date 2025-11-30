<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizOption extends Model
{
    use HasFactory;

    protected $table = 'quiz_options';
    
    // Tabel ini tidak punya timestamps sama sekali
    public $timestamps = false;

    protected $fillable = [
        'quizId',
        'optionId', // A, B, C, D
        'text'
    ];

    /**
     * Relasi balik ke QuizCard
     */
    public function quiz()
    {
        return $this->belongsTo(QuizCard::class, 'quizId', 'id');
    }
}