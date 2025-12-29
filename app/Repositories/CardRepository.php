<?php
namespace App\Repositories;

use App\Models\Card;
use App\Models\QuizCard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CardRepository
{
    // --- RISK & CHANCE (Pakai Eloquent withCount) ---
    public function getCardsPaginated($type, $limit, $filters = [])
    {
        $query = Card::where('type', strtoupper($type));

        if (!empty($filters['search'])) {
            $query->where('title', 'like', "%{$filters['search']}%");
        }
        if (!empty($filters['difficulty'])) {
            $query->where('difficulty', $filters['difficulty']);
        }

        // Hitung usage secara otomatis lewat relasi (gunakan type_card format)
        return $query->withCount([
            'decisions' => function (Builder $q) use ($type) {
                $q->where('content_type', $type . '_card');
            }
        ])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    public function findCardById($id, $type)
    {
        return Card::where('id', $id)->where('type', strtoupper($type))->first();
    }

    public function getCardStats($id, $type)
    {
        return DB::table('player_decisions')
            ->where('content_id', $id)
            ->where('content_type', $type . '_card')
            ->count();
    }

    // --- QUIZ (Pakai SelectSub agar aman Strict Mode) ---
    public function getQuizzesPaginated($limit, $filters = [])
    {
        $query = QuizCard::query();

        if (!empty($filters['search'])) {
            $query->where('question', 'like', "%{$filters['search']}%");
        }
        if (!empty($filters['difficulty'])) {
            $query->where('difficulty', $filters['difficulty']);
        }

        // Hapus withCount, ganti full dengan selectSub
        return $query
            // 1. Hitung Total Attempts (Dari tabel player_decisions)
            ->selectSub(function ($q) {
                $q->from('player_decisions')
                    ->whereColumn('player_decisions.content_id', 'quiz_cards.id')
                    ->where('player_decisions.content_type', 'quiz')
                    ->selectRaw('count(*)');
            }, 'total_attempts')

            // 2. Hitung Accuracy (Rata-rata jawaban benar)
            ->selectSub(function ($q) {
                $q->from('player_decisions')
                    ->whereColumn('player_decisions.content_id', 'quiz_cards.id')
                    ->where('player_decisions.content_type', 'quiz')
                    ->selectRaw('COALESCE(AVG(is_correct) * 100, 0)');
            }, 'accuracy')

            // 3. Pastikan data kuis tetap terambil
            ->addSelect('quiz_cards.*')

            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    public function findQuizById($id)
    {
        return QuizCard::with('options')->find($id);
    }

    // CREATE
    public function createCard($data)
    {
        return Card::create($data);
    }

    public function createQuiz($data, $options)
    {
        return DB::transaction(function () use ($data, $options) {
            $quiz = QuizCard::create($data);
            foreach ($options as &$opt) {
                $opt['quizId'] = $quiz->id;
            }
            $quiz->options()->createMany($options);
            return $quiz;
        });
    }

    // UPDATE
    public function updateCard($id, $data)
    {
        $card = Card::find($id);
        if (!$card)
            return null;
        $card->update($data);
        return $card;
    }

    public function updateQuiz($id, $data, $options)
    {
        return DB::transaction(function () use ($id, $data, $options) {
            $quiz = QuizCard::findOrFail($id);
            $quiz->update($data);

            if (!empty($options)) {
                $quiz->options()->delete();
                foreach ($options as &$opt) {
                    $opt['quizId'] = $quiz->id;
                }
                $quiz->options()->createMany($options);
            }
            return $quiz;
        });
    }

    // DELETE
    public function deleteCard($id, $type)
    {
        return Card::where('id', $id)->where('type', strtoupper($type))->delete();
    }

    public function deleteQuiz($id)
    {
        $quiz = QuizCard::find($id);
        if ($quiz) {
            $quiz->options()->delete(); // Hapus opsi dulu
            return $quiz->delete();
        }
        return false;
    }
}