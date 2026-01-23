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

        // Get category from board tile based on target_tile
        $category = null;
        if ($card->target_tile) {
            $tile = \DB::table('boardtiles')->where('tile_id', $card->target_tile)->first();
            if ($tile && $tile->category) {
                $category = $tile->category;
            }
        }

        $data = [
            'id' => $card->id,
            'title' => $card->title,
            'description' => $card->narration,
            'action_type' => $card->action,
            'target_tile' => (int) $card->target_tile,
            'categories' => $category,
            'difficulty' => (int) $card->difficulty,
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
        // Mapping type ke prefix ID
        $typeUpper = strtoupper($type);
        $prefix = ($typeUpper === 'RISK') ? 'CARD_RISK' : 'CARD_OPP';
        
        // Cari nomor sequence terakhir dengan prefix yang sama
        $lastCard = \App\Models\Card::where('id', 'LIKE', "{$prefix}_%")
            ->orderBy('id', 'desc')
            ->first();
        
        // Generate nomor baru
        $nextNumber = 1;
        if ($lastCard) {
            // Extract angka dari ID terakhir (CARD_RISK_01 -> 01 atau CARD_OPP_01 -> 01)
            preg_match('/' . $prefix . '_(\d+)$/', $lastCard->id, $matches);
            if (!empty($matches[1])) {
                $nextNumber = intval($matches[1]) + 1;
            }
        }
        
        // Format ID: CARD_RISK_XX atau CARD_OPP_XX (dengan padding 2 digit)
        $id = $prefix . '_' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        // Validate target_tile is required
        if (!isset($data['target_tile']) || $data['target_tile'] === null) {
            throw new \Exception('Target tile is required for all cards');
        }

        // Get category from board tile based on target_tile
        $category = null;
        $tile = \DB::table('boardtiles')->where('tile_id', $data['target_tile'])->first();
        if ($tile && $tile->category) {
            $category = $tile->category;
        }

        $cardData = [
            'id' => $id,
            'type' => $typeUpper,
            'title' => $data['title'],
            'narration' => $data['narration'] ?? $data['effect'] ?? '',
            'action' => $data['action'] ?? 'default',
            'target_tile' => (int) $data['target_tile'], // Ensure it's stored as position_index integer
            'categories' => $category,
            'difficulty' => $data['difficulty'] ?? 1,
            'scoreChange' => $data['scoreChange'] ?? 0,
            'tags' => $data['tags'] ?? '',
            'expected_benefit' => $data['expected_benefit'] ?? 5,
            'learning_objective' => $data['learning_objective'] ?? '',
        ];
        return $this->repo->createCard($cardData);
    }

    public function createQuiz($data)
    {
        // Generate ID dengan format QUIZ_XX
        $prefix = 'QUIZ';
        
        // Ambil semua quiz dengan prefix QUIZ_ dan extract angkanya
        $allQuizzes = \App\Models\QuizCard::where('id', 'LIKE', "{$prefix}_%")
            ->pluck('id');
        
        // Generate nomor baru
        $maxNumber = 0;
        foreach ($allQuizzes as $quizId) {
            // Extract angka dari ID (QUIZ_01 -> 01, QUIZ_20 -> 20)
            if (preg_match('/' . $prefix . '_(\d+)$/', $quizId, $matches)) {
                $number = intval($matches[1]);
                if ($number > $maxNumber) {
                    $maxNumber = $number;
                }
            }
        }
        
        $nextNumber = $maxNumber + 1;
        
        // Format ID: QUIZ_XX (dengan padding 2 digit)
        $id = $prefix . '_' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

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

        // Validate target_tile is required
        if (isset($data['target_tile']) && $data['target_tile'] === null) {
            throw new \Exception('Target tile cannot be null');
        }

        // Get category from board tile if target_tile is being updated
        $category = $card->categories;
        if (isset($data['target_tile'])) {
            $tile = \DB::table('boardtiles')->where('tile_id', $data['target_tile'])->first();
            if ($tile && $tile->category) {
                $category = $tile->category;
            }
        }

        $updateData = array_filter([
            'title' => $data['title'] ?? $card->title,
            'narration' => $data['narration'] ?? $data['effect'] ?? $card->narration,
            'action' => $data['action'] ?? $card->action,
            'target_tile' => isset($data['target_tile']) ? (int) $data['target_tile'] : $card->target_tile,
            'categories' => $category,
            'difficulty' => $data['difficulty'] ?? $card->difficulty,
            'scoreChange' => $data['scoreChange'] ?? $card->scoreChange,
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