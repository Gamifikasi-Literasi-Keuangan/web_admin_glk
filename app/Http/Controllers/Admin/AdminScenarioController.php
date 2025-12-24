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

        if (!$data) {
            return response()->json(['message' => 'Scenario not found'], 404);
        }

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'question' => 'required|string',
            'difficulty' => 'required|integer|between:1,3',
            'options' => 'required|array|min:2',
            'options.*.optionId' => 'required|string',
            'options.*.text' => 'required|string',
            'options.*.is_correct' => 'required|boolean',
        ]);

        $scenario = $this->service->create($request->all());
        return response()->json(['message' => 'Scenario created', 'data' => $scenario], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'category' => 'sometimes|string',
            'question' => 'sometimes|string',
            'difficulty' => 'sometimes|nullable|integer|between:1,3',
            'options' => 'sometimes|array|min:2',
            'options.*.optionId' => 'sometimes|string',
            'options.*.text' => 'sometimes|string',
            'options.*.is_correct' => 'sometimes|boolean',
            'options.*.response' => 'sometimes|nullable|string',
            'options.*.scoreChange' => 'sometimes|nullable',
        ]);

        $scenario = $this->service->update($id, $request->all());
        if (!$scenario) {
            return response()->json(['message' => 'Scenario not found'], 404);
        }
        return response()->json(['message' => 'Scenario updated', 'data' => $scenario]);
    }

    public function destroy($id)
    {
        $deleted = $this->service->delete($id);
        if (!$deleted) {
            return response()->json(['message' => 'Scenario not found'], 404);
        }
        return response()->json(['message' => 'Scenario deleted']);
    }
}