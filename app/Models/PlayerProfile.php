<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerProfile extends Model
{
    protected $table = 'playerprofile'; // Sesuai skema SQL
    protected $primaryKey = 'PlayerId';
    public $incrementing = false;
    public $timestamps = false; // Karena ada last_updated manual

    protected $casts = [
        'traits' => 'array',
        'weak_areas' => 'array',
        'lifetime_scores' => 'array',
        'onboarding_answers' => 'array'
    ];

    public function player()
    {
        return $this->belongsTo(Player::class, 'PlayerId', 'PlayerId');
    }
}