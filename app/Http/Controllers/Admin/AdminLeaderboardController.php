<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GameOperationService;
use Illuminate\Http\Request;

class AdminLeaderboardController extends Controller
{
    protected $service;

    public function __construct(GameOperationService $service)
    {
        $this->service = $service;
    }


    public function globalLeaderboard(Request $request)
    {
        $limit = $request->input('limit', 50);
        $data = $this->service->getLeaderboard($limit);
        
        return response()->json([
            'limit' => (int) $limit,
            'rankings' => $data
        ]);
    }
}