<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TileService;

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
}