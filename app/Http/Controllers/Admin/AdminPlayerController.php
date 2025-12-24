<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PlayerService;
use Illuminate\Http\Request;

class AdminPlayerController extends Controller
{
    protected $service;

    public function __construct(PlayerService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data = $this->service->getList($request);
        return response()->json($data);
    }

    public function show($id)
    {
        $data = $this->service->getDetail($id);
        if (!$data) return response()->json(['message' => 'Not Found'], 404);
        return response()->json($data);
    }

    public function analysis($id)
    {
        $data = $this->service->getAnalysis($id);
        if (!$data) return response()->json(['message' => 'Not Found'], 404);
        return response()->json($data);
    }

    public function destroy($id)
    {
        $result = $this->service->deletePlayer($id);
        if (!$result) {
            return response()->json(['message' => 'Player not found'], 404);
        }
        return response()->json($result);
    }

    public function ban(Request $request, $id)
    {
        $request->validate([
            'ban_reason' => 'nullable|string|max:500'
        ]);

        $result = $this->service->banPlayer($id, $request->input('ban_reason'));
        if (!$result) {
            return response()->json(['message' => 'Player not found'], 404);
        }
        return response()->json($result);
    }

    public function unban($id)
    {
        $result = $this->service->unbanPlayer($id);
        if (!$result) {
            return response()->json(['message' => 'Player not found'], 404);
        }
        return response()->json($result);
    }
}