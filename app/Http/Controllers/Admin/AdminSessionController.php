<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GameOperationService;
use Illuminate\Http\Request;

class AdminSessionController extends Controller
{
    protected $service;

    public function __construct(GameOperationService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return response()->json($this->service->getSessionList($request));
    }
        public function leaderboard($id)
    {
        $leaderboard = $this->service->getSessionLeaderboard($id);
        
        if ($leaderboard->isEmpty()) {
            return response()->json(['message' => 'Session not found or no participants'], 404);
        }

        return response()->json([
            'session_id' => $id,
            'data' => $leaderboard
        ]);
    }

    public function show($id)
    {
        $data = $this->service->getSessionDetail($id);
        return $data ? response()->json($data) : response()->json(['message' => 'Not Found'], 404);
    }
}