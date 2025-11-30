<?php
namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    protected $repo;

    public function __construct(AuthRepository $repo)
    {
        $this->repo = $repo;
    }

    public function login($credentials)
    {
        $user = $this->repo->findByUsername($credentials['username']);

        // Manual hash check karena kolom passwordHash custom
        if (!$user || !Hash::check($credentials['password'], $user->passwordHash)) {
            return null;
        }

        if ($user->role !== 'admin') {
            return 'unauthorized';
        }

        // Generate Token
        $token = JWTAuth::fromUser($user);
        return ['token' => $token, 'user' => $user];
    }
}