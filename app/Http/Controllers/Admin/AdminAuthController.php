<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $result = $this->service->login($request->only('username', 'password'));

        if (!$result) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        if ($result === 'unauthorized') {
            return response()->json(['error' => 'Not an admin account'], 403);
        }

        return response()->json([
            'success' => true,
            'token' => $result['token'],
            'user' => [
                'id' => $result['user']->id,
                'username' => $result['user']->username,
                'role' => $result['user']->role
            ]
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['success' => true]);
    }
}