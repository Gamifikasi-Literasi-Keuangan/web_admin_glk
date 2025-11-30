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
            
            if ($type === 'risk') $data['impact'] = (int) $card->scoreChange;
            else $data['benefit'] = (int) $card->scoreChange;
            
            return $data;
        });

        return $paginator;
    }

    public function getDetail($id, $type)
    {
        $card = $this->repo->findCardById($id, $type);
        if (!$card) return null;

        $stats = $this->repo->getCardStats($id, $type);

        $data = [
            'id' => $card->id,
            'title' => $card->title,
            'description' => $card->narration,
            'action_type' => $card->action, // Tambahan agar tidak undefined di JS
            'difficulty' => (int) $card->difficulty,
            'stats' => ['landed_count' => (int) $stats]
        ];

        if ($type === 'risk') $data['impact'] = (int) $card->scoreChange;
        else $data['benefit'] = (int) $card->scoreChange;

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
        if (!$quiz) return null;

        return [
            'id' => $quiz->id,
            'question' => $quiz->question,
            
            // PERBAIKAN UTAMA DI SINI:
            // Mapping nama kolom DB ke nama variabel yang diminta JS (content.js)
            'correct_option_id' => $quiz->correctOption, // Supaya kunci jawaban hijau muncul
            'correct_score' => (int) $quiz->correctScore, // Supaya Score Benar muncul
            'incorrect_score' => (int) $quiz->incorrectScore, // Supaya Score Salah muncul
            
            'difficulty' => (int) $quiz->difficulty,
            'options' => $quiz->options->map(function($opt) {
                return [
                    'id' => $opt->id, 
                    'label' => $opt->optionId, 
                    'text' => $opt->text
                ];
            })
        ];
    }
}