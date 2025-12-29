<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TileService;
use Illuminate\Http\Request;

class AdminTileController extends Controller
{
    protected $service;

    public function __construct(TileService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json(['data' => $this->service->getAllTiles()]);
    }

    public function show($id)
    {
        $data = $this->service->getTileDetail($id);
        return $data ? response()->json($data) : response()->json(['message' => 'Not Found'], 404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:scenario,risk,chance,quiz,start,finish',
            'position' => 'required|integer|min:0',
            'content_type' => 'nullable|string',
            'content_id' => 'nullable|integer',
            'category' => 'nullable|string|max:100' // For scenario tiles
        ]);

        $result = $this->service->createTile($validated);
        
        if ($result['success']) {
            return response()->json($result, 201);
        }
        return response()->json($result, 400);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:scenario,risk,chance,quiz,start,finish',
            'position' => 'required|integer|min:0',
            'content_type' => 'nullable|string',
            'content_id' => 'nullable|integer',
            'category' => 'nullable|string|max:100' // For scenario tiles
        ]);

        $result = $this->service->updateTile($id, $validated);
        
        if ($result['success']) {
            return response()->json($result);
        }
        return response()->json($result, 400);
    }

    public function destroy($id)
    {
        $result = $this->service->deleteTile($id);
        if ($result['success']) {
            return response()->json($result);
        }
        return response()->json($result, 400);
    }

    public function contents()
    {
        return response()->json(['data' => $this->service->getAvailableContents()]);
    }
}