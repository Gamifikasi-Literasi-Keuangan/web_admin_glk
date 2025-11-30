<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $table = 'config';
    
    // Config hanya punya updated_at, tidak ada created_at di definisinya
    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'minPlayers',
        'maxPlayers',
        'max_turns',
        'version'
    ];

    protected $casts = [
        'minPlayers' => 'integer',
        'maxPlayers' => 'integer',
        'max_turns' => 'integer',
        'version' => 'integer',
    ];
}