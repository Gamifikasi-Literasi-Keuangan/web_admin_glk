<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turn extends Model
{
    protected $table = 'turns';
    protected $primaryKey = 'turn_id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // Timestamp custom
    const CREATED_AT = 'started_at';
    const UPDATED_AT = null;

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'PlayerId');
    }
}