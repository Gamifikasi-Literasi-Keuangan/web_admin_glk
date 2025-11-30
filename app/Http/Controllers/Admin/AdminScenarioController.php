<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ScenarioService;
use Illuminate\Http\Request;

class AdminScenarioController extends Controller
{
    protected $service;

    public function __construct(ScenarioService $service)
    {
        $this->service = $service;
    }


    public function index(Request $request)
    {
        $data = $this->service->getAdminList($request); 
        return response()->json($data);
    }


    public function show($id)
    {
        $data = $this->service->getAdminDetail($id);
        
        if(!$data) {
            return response()->json(['message' => 'Scenario not found'], 404);
        }

        return response()->json(['data' => $data]);
    }
}