<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $table = 'players';
    protected $primaryKey = 'PlayerId';
    public $incrementing = false;
    protected $keyType = 'string';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;

    protected $fillable = ['PlayerId', 'user_id', 'name', 'gamesPlayed', 'initial_platform', 'locale'];

    public function user()
    {
        return $this->belongsTo(AuthUser::class, 'user_id', 'id');
    }

    public function profile()
    {
        return $this->hasOne(PlayerProfile::class, 'PlayerId', 'PlayerId');
    }
    public function participations()
    {
        return $this->hasMany(ParticipatesIn::class, 'playerId', 'PlayerId');
    }
}