<?php
namespace App\Services;

use App\Repositories\CardRepository;

class CardService
{
    protected $repo;

    public function __construct(CardRepository $repo)
    {
        $this->repo = $repo;
    }

    // --- RISK & CHANCE ---
    public function getList($type, $request)
    {
        $paginator = $this->repo->getCardsPaginated($type, $request->input('limit', 10), [
            'search' => $request->input('search'),
            'difficulty' => $request->input('difficulty')
        ]);

        $paginator->getCollection()->transform(function ($card) use ($type) {
            $data = [
                'id' => $card->id,
                'title' => $card->title,
                'difficulty' => (int) $card->difficulty,
                'usage' => (int) ($card->decisions_count ?? 0)
            ];

            if ($type === 'risk')
                $data['impact'] = (int) $card->scoreChange;
            else
                $data['benefit'] = (int) $card->scoreChange;

            return $data;
        });

        return $paginator;
    }

    public function getDetail($id, $type)
    {
        $card = $this->repo->findCardById($id, $type);
        if (!$card)
            return null;

        $stats = $this->repo->getCardStats($id, $type);

        $data = [
            'id' => $card->id,
            'title' => $card->title,
            'description' => $card->narration,
            'action_type' => $card->action,
            'difficulty' => (int) $card->difficulty,
            'categories' => $card->categories,
            'tags' => $card->tags,
            'expected_benefit' => (int) ($card->expected_benefit ?? 5),
            'learning_objective' => $card->learning_objective ?? '',
            'stats' => ['landed_count' => (int) $stats]
        ];

        if ($type === 'risk')
            $data['impact'] = (int) $card->scoreChange;
        else
            $data['benefit'] = (int) $card->scoreChange;

        return $data;
    }

    // --- QUIZ ---
    public function getQuizList($request)
    {
        $paginator = $this->repo->getQuizzesPaginated($request->input('limit', 10), [
            'search' => $request->input('search'),
            'difficulty' => $request->input('difficulty')
        ]);

        $paginator->getCollection()->transform(function ($q) {
            return [
                'id' => $q->id,
                'question' => $q->question,
                'accuracy' => ($q->total_attempts > 0) ? round($q->accuracy, 1) . '%' : '-',
                'total_attempts' => (int) $q->total_attempts
            ];
        });

        return $paginator;
    }

    public function getQuizDetail($id)
    {
        $quiz = $this->repo->findQuizById($id);
        if (!$quiz)
            return null;

        return [
            'id' => $quiz->id,
            'question' => $quiz->question,

            // PERBAIKAN UTAMA DI SINI:
            // Mapping nama kolom DB ke nama variabel yang diminta JS (content.js)
            'correct_option_id' => $quiz->correctOption, // Supaya kunci jawaban hijau muncul
            'correct_score' => (int) $quiz->correctScore, // Supaya Score Benar muncul
            'incorrect_score' => (int) $quiz->incorrectScore, // Supaya Score Salah muncul

            'difficulty' => (int) $quiz->difficulty,
            'tags' => $quiz->tags,
            'learning_objective' => $quiz->learning_objective,
            'options' => $quiz->options->map(function ($opt) {
                return [
                    'id' => $opt->id,
                    'label' => $opt->optionId,
                    'text' => $opt->text
                ];
            })
        ];
    }

    // CREATE, UPDATE, DELETE
    public function createCard($type, $data)
    {
        $cardData = [
            'id' => strtoupper($type) . '_' . uniqid(),
            'type' => strtoupper($type),
            'title' => $data['title'],
            'narration' => $data['narration'] ?? $data['effect'] ?? '',
            'action' => $data['action'] ?? 'default',
            'difficulty' => $data['difficulty'] ?? 1,
            'scoreChange' => $data['scoreChange'] ?? 0,
            'categories' => $data['categories'] ?? '',
            'tags' => $data['tags'] ?? '',
            'expected_benefit' => $data['expected_benefit'] ?? 5,
            'learning_objective' => $data['learning_objective'] ?? '',
        ];
        return $this->repo->createCard($cardData);
    }

    public function createQuiz($data)
    {
        // Generate unique ID for quiz
        $id = 'quiz_' . uniqid();

        $quizData = [
            'id' => $id,
            'question' => $data['question'],
            'difficulty' => $data['difficulty'],
            'correctOption' => $data['correctOption'] ?? 'A',
            'correctScore' => $data['correctScore'] ?? 10,
            'incorrectScore' => $data['incorrectScore'] ?? -5,
            'tags' => $data['tags'] ?? '',
            'learning_objective' => $data['learning_objective'] ?? '',
        ];
        return $this->repo->createQuiz($quizData, $data['options']);
    }

    public function updateCard($id, $type, $data)
    {
        $card = $this->repo->findCardById($id, $type);
        if (!$card)
            return null;

        $updateData = array_filter([
            'title' => $data['title'] ?? $card->title,
            'narration' => $data['narration'] ?? $data['effect'] ?? $card->narration,
            'action' => $data['action'] ?? $card->action,
            'difficulty' => $data['difficulty'] ?? $card->difficulty,
            'scoreChange' => $data['scoreChange'] ?? $card->scoreChange,
            'categories' => $data['categories'] ?? $card->categories,
            'tags' => $data['tags'] ?? $card->tags,
            'expected_benefit' => $data['expected_benefit'] ?? $card->expected_benefit,
            'learning_objective' => $data['learning_objective'] ?? $card->learning_objective,
        ]);
        return $this->repo->updateCard($id, $updateData);
    }

    public function updateQuiz($id, $data)
    {
        $quiz = $this->repo->findQuizById($id);
        if (!$quiz)
            return null;

        $updateData = array_filter([
            'question' => $data['question'] ?? $quiz->question,
            'difficulty' => $data['difficulty'] ?? $quiz->difficulty,
            'correctOption' => $data['correctOption'] ?? $quiz->correctOption,
            'correctScore' => $data['correctScore'] ?? $quiz->correctScore,
            'incorrectScore' => $data['incorrectScore'] ?? $quiz->incorrectScore,
            'tags' => $data['tags'] ?? $quiz->tags,
            'learning_objective' => $data['learning_objective'] ?? $quiz->learning_objective,
        ]);

        $options = $data['options'] ?? [];
        return $this->repo->updateQuiz($id, $updateData, $options);
    }

    public function deleteCard($id, $type)
    {
        return $this->repo->deleteCard($id, $type);
    }

    public function deleteQuiz($id)
    {
        return $this->repo->deleteQuiz($id);
    }
}