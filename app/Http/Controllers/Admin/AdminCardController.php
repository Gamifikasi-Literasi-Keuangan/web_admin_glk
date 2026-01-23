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

    // CREATE
    public function storeRisk(Request $request)
    {
        \Log::info('Risk card request received:', $request->all());
        
        $request->validate([
            'title' => 'required|string|max:255',
            'narration' => 'required|string',
            'difficulty' => 'required|integer|between:1,3',
            'target_tile' => 'required|integer|min:0',
        ]);
        $card = $this->service->createCard('risk', $request->all());
        return response()->json(['message' => 'Risk card created', 'data' => $card], 201);
    }

    public function storeChance(Request $request)
    {
        \Log::info('Chance card request received:', $request->all());
        
        $request->validate([
            'title' => 'required|string|max:255',
            'narration' => 'required|string',
            'difficulty' => 'required|integer|between:1,3',
            'target_tile' => 'required|integer|min:0',
        ]);
        $card = $this->service->createCard('chance', $request->all());
        return response()->json(['message' => 'Chance card created', 'data' => $card], 201);
    }

    public function storeQuiz(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'difficulty' => 'required|integer|between:1,3',
            'options' => 'required|array|min:2',
            'options.*.text' => 'required|string',
            'options.*.is_correct' => 'required|boolean',
            'tags' => 'nullable|string',
            'learning_objective' => 'nullable|string',
        ]);
        $quiz = $this->service->createQuiz($request->all());
        return response()->json(['message' => 'Quiz created', 'data' => $quiz], 201);
    }

    // UPDATE
    public function updateRisk(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'narration' => 'sometimes|string',
            'difficulty' => 'sometimes|integer|between:1,3',
            'scoreChange' => 'sometimes|integer',
            'target_tile' => 'sometimes|integer|min:0',
        ]);
        $card = $this->service->updateCard($id, 'risk', $request->all());
        if (!$card)
            return response()->json(['message' => 'Not Found'], 404);
        return response()->json(['message' => 'Risk card updated', 'data' => $card]);
    }

    public function updateChance(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'narration' => 'sometimes|string',
            'difficulty' => 'sometimes|integer|between:1,3',
            'scoreChange' => 'sometimes|integer',
            'target_tile' => 'sometimes|integer|min:0',
        ]);
        $card = $this->service->updateCard($id, 'chance', $request->all());
        if (!$card)
            return response()->json(['message' => 'Not Found'], 404);
        return response()->json(['message' => 'Chance card updated', 'data' => $card]);
    }

    public function updateQuiz(Request $request, $id)
    {
        $request->validate([
            'question' => 'sometimes|string',
            'difficulty' => 'sometimes|integer|between:1,3',
            'correctScore' => 'sometimes|integer',
            'incorrectScore' => 'sometimes|integer',
            'correctOption' => 'sometimes|string',
            'options' => 'sometimes|array|min:2',
            'options.*.optionId' => 'sometimes|string',
            'options.*.text' => 'sometimes|string',
            'tags' => 'nullable|string',
            'learning_objective' => 'nullable|string',
        ]);
        $quiz = $this->service->updateQuiz($id, $request->all());
        if (!$quiz)
            return response()->json(['message' => 'Not Found'], 404);
        return response()->json(['message' => 'Quiz updated', 'data' => $quiz]);
    }

    // DELETE
    public function destroyRisk($id)
    {
        $deleted = $this->service->deleteCard($id, 'risk');
        if (!$deleted)
            return response()->json(['message' => 'Not Found'], 404);
        return response()->json(['message' => 'Risk card deleted']);
    }

    public function destroyChance($id)
    {
        $deleted = $this->service->deleteCard($id, 'chance');
        if (!$deleted)
            return response()->json(['message' => 'Not Found'], 404);
        return response()->json(['message' => 'Chance card deleted']);
    }

    public function destroyQuiz($id)
    {
        $deleted = $this->service->deleteQuiz($id);
        if (!$deleted)
            return response()->json(['message' => 'Not Found'], 404);
        return response()->json(['message' => 'Quiz deleted']);
    }
}