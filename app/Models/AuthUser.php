<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject; // Asumsi pakai JWT

class AuthUser extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'auth_users';
    protected $fillable = ['username', 'passwordHash', 'role', 'is_active', 'ban_reason'];
    protected $hidden = ['passwordHash'];

    // Override kolom password default Laravel
    public function getAuthPassword()
    {
        return $this->passwordHash;
    }

    // JWT Methods
    public function getJWTIdentifier() { return $this->getKey(); }
    public function getJWTCustomClaims() { return ['role' => $this->role]; }

    public function player()
    {
        return $this->hasOne(Player::class, 'user_id', 'id');
    }
}