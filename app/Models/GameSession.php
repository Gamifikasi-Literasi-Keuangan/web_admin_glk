<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    use HasFactory;

    protected $table = 'game_sessions'; // Nama tabel yang benar
    protected $primaryKey = 'sessionId'; // PK String
    public $incrementing = false;
    protected $keyType = 'string';

    // Custom Timestamp
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'sessionId', 'host_player_id', 'max_players', 'max_turns', 
        'status', 'current_player_id', 'current_turn', 
        'game_state', 'started_at', 'ended_at'
    ];

    protected $casts = [
        'game_state' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime'
    ];

    // Relasi ke Player (Host)
    public function host()
    {
        return $this->belongsTo(Player::class, 'host_player_id', 'PlayerId');
    }

    // Relasi ke Peserta
    public function participants()
    {
        return $this->hasMany(ParticipatesIn::class, 'sessionId', 'sessionId');
    }
    
    // Relasi ke Turns (Log Giliran)
    public function turns()
    {
        return $this->hasMany(Turn::class, 'session_id', 'sessionId');
    }
}