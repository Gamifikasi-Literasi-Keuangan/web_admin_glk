<?php
namespace App\Services;

use App\Repositories\ScenarioRepository;
use App\Models\ScenarioOption;

class ScenarioService
{
    protected $repo;

    public function __construct(ScenarioRepository $repo)
    {
        $this->repo = $repo;
    }

    public function processSubmission(array $data)
    {
        $option = ScenarioOption::where('scenarioId', $data['scenario_id'])
            ->where('optionId', $data['selected_option'])
            ->first();

        if (!$option) {
            throw new \Exception("Opsi jawaban tidak ditemukan.");
        }

        $scoreChangeArray = $option->scoreChange;
        $affectedScore = array_key_first($scoreChangeArray);
        $scoreChange = $scoreChangeArray[$affectedScore] ?? 0;

        $newScoreValue = 12; // Mock sementara

        $feedbackText = $option->response ?? "Pilihan tercatat.";
        $responseMessage = $option->is_correct ? $feedbackText : $feedbackText;

        return [
            'correct' => (bool) $option->is_correct,
            'score_change' => $scoreChange,
            'affected_score' => $affectedScore,
            'new_score_value' => $newScoreValue,
            'response' => $responseMessage
        ];
    }


    public function getAdminList($request)
    {
        // Ambil Data Mentah dari Repository dengan Filter
        $paginator = $this->repo->getPaginated($request->input('limit', 10), [
            'search' => $request->input('search'),
            'category' => $request->input('category'),
            'difficulty' => $request->input('difficulty'),
        ]);

        // Transformasi Data agar rapi untuk API Response
        $paginator->getCollection()->transform(function ($scenario) {
            return [
                'id' => $scenario->id,
                'title' => $scenario->title,
                'category' => $scenario->category,
                'difficulty' => $scenario->difficulty,
                'score' => $scenario->expected_benefit,
                'options_count' => $scenario->options->count(),
                'created_at' => $scenario->created_at ? $scenario->created_at->format('Y-m-d H:i') : '-',
            ];
        });

        return $paginator;
    }

    /**
     * Mengambil detail lengkap skenario
     */
    public function getAdminDetail($id)
    {
        $scenario = $this->repo->findById($id);

        if (!$scenario)
            return null;

        return [
            'id' => $scenario->id,
            'meta' => [
                'created_at' => $scenario->created_at,
                'updated_at' => $scenario->updated_at,
            ],
            'content' => [
                'title' => $scenario->title,
                'category' => $scenario->category,
                'question' => $scenario->question,
                'difficulty' => $scenario->difficulty,
                'score' => $scenario->expected_benefit,
                'learning_objective' => $scenario->learning_objective,
            ],
            'ai_config' => [
                'tags' => $scenario->tags,
                'weak_area_relevance' => $scenario->weak_area_relevance,
                'cluster_relevance' => $scenario->cluster_relevance,
                'historical_success_rate' => $scenario->historical_success_rate
            ],
            'options' => $scenario->options->map(function ($opt) {
                return [
                    'id' => $opt->id,
                    'label' => $opt->optionId,
                    'text' => $opt->text,
                    'feedback' => $opt->response,
                    'is_correct' => (bool) $opt->is_correct,
                    'impact' => $opt->scoreChange
                ];
            })
        ];
    }

    public function create($data)
    {
        // Generate unique ID untuk scenario
        $category = strtolower($data['category']);
        $id = $category . '_' . uniqid();

        $scenarioData = [
            'id' => $id,
            'title' => $data['title'],
            'category' => $data['category'],
            'question' => $data['question'],
            'difficulty' => $data['difficulty'],
            'expected_benefit' => $data['expected_benefit'] ?? 10,
            'learning_objective' => $data['learning_objective'] ?? null,
            'tags' => $data['tags'] ?? [],
            'weak_area_relevance' => $data['weak_area_relevance'] ?? [],
            'cluster_relevance' => $data['cluster_relevance'] ?? [],
            'historical_success_rate' => $data['historical_success_rate'] ?? 0.5,
        ];

        return $this->repo->createWithOptions($scenarioData, $data['options']);
    }

    public function update($id, $data)
    {
        $scenario = $this->repo->findById($id);
        if (!$scenario)
            return null;

        $scenarioData = array_filter([
            'title' => $data['title'] ?? $scenario->title,
            'category' => $data['category'] ?? $scenario->category,
            'question' => $data['question'] ?? $scenario->question,
            'difficulty' => $data['difficulty'] ?? $scenario->difficulty,
            'expected_benefit' => $data['expected_benefit'] ?? $scenario->expected_benefit,
            'learning_objective' => $data['learning_objective'] ?? $scenario->learning_objective,
            'tags' => $data['tags'] ?? $scenario->tags,
            'weak_area_relevance' => $data['weak_area_relevance'] ?? $scenario->weak_area_relevance,
            'cluster_relevance' => $data['cluster_relevance'] ?? $scenario->cluster_relevance,
        ]);

        $options = $data['options'] ?? [];
        return $this->repo->updateWithOptions($id, $scenarioData, $options);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}