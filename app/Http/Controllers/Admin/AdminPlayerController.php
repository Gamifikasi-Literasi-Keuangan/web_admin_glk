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
}