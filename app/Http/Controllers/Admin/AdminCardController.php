<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CardService;
use Illuminate\Http\Request;

class AdminCardController extends Controller
{
    protected $service;

    public function __construct(CardService $service)
    {
        $this->service = $service;
    }

    // Risk
    public function indexRisk(Request $request)
    {
        return response()->json($this->service->getList('risk', $request));
    }
    public function showRisk($id)
    {
        $data = $this->service->getDetail($id, 'risk');
        return $data ? response()->json($data) : response()->json(['message' => 'Not Found'], 404);
    }

    // Chance
    public function indexChance(Request $request)
    {
        return response()->json($this->service->getList('chance', $request));
    }
    public function showChance($id)
    {
        $data = $this->service->getDetail($id, 'chance');
        return $data ? response()->json($data) : response()->json(['message' => 'Not Found'], 404);
    }

    // Quiz
    public function indexQuiz(Request $request)
    {
        return response()->json($this->service->getQuizList($request));
    }
    public function showQuiz($id)
    {
        $data = $this->service->getQuizDetail($id);
        return $data ? response()->json($data) : response()->json(['message' => 'Not Found'], 404);
    }
}