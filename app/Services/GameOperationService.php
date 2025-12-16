<?php
namespace App\Services;

use App\Repositories\SessionRepository;
use Carbon\Carbon;

class GameOperationService
{
    protected $repo;

    public function __construct(SessionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getSessionList($request)
    {
        $limit = $request->input('limit', 20);
        $filters = [
            'status' => $request->input('status'),
            'date_from' => $request->input('date_from')
        ];

        $paginator = $this->repo->getPaginated($limit, $filters);

        $paginator->getCollection()->transform(function ($session) {
            $duration = 0;
            if ($session->started_at && $session->ended_at) {
                $duration = Carbon::parse($session->started_at)->diffInSeconds(Carbon::parse($session->ended_at));
            }

            return [
                'session_id' => $session->sessionId,
                'status' => ucfirst($session->status),
                'host' => $session->host_name,
                'winner' => $session->winner_name ?? '-',
                'winning_score' => (int) $session->winning_score,
                'duration_human' => $duration > 0 ? gmdate("H:i:s", $duration) : '-',
                'played_at' => $session->created_at,
            ];
        });

        return $paginator;
    }

    public function getSessionDetail($id)
    {
        $session = $this->repo->findById($id);
        if (!$session)
            return null;

        // Hitung Durasi
        $duration = '-';
        if ($session->started_at && $session->ended_at) {
            $minutes = abs($session->ended_at->diffInMinutes($session->started_at));
            $duration = $minutes . ' menit';
        }

        // Format Timeline dari Turns
        $timeline = $session->turns->map(function ($turn) {
            return [
                'turn_number' => $turn->turn_number,
                'player' => $turn->player->name ?? 'Unknown Player',
                'timestamp' => $turn->started_at ? $turn->started_at->format('H:i:s') : '-',
                // Disini nanti bisa dikembangkan ambil detail activity dari player_decisions
                'activity' => [
                    'dice_roll' => null, // Atau ambil dari telemetry jika ada
                    'decisions' => []    // Atau ambil dari player_decisions jika ada
                ]
            ];
        });

        // PERBAIKAN STRUKTUR DATA DI SINI
        return [
            'session_info' => [
                'id' => $session->sessionId,
                'status' => ucfirst($session->status),
                // Gunakan null coalescing (??) agar tidak error jika host terhapus
                'host' => $session->host->name ?? 'Unknown Host',
                'created_at' => $session->created_at ? $session->created_at->toDateTimeString() : '-',
                'started_at' => $session->started_at,
                'ended_at' => $session->ended_at,
                'duration' => $duration,
                'total_turns' => $session->current_turn ?? 0,
            ],
            'leaderboard' => $session->participants->map(function ($p) {
                return [
                    'name' => $p->player->name ?? 'Unknown',
                    'score' => (int) $p->score,
                    'rank' => (int) $p->rank,
                    'final_tile_position' => (int) $p->position,
                    'status' => $p->connection_status
                ];
            })->sortBy('rank')->values(),
            'timeline_logs' => $timeline
        ];
    }

    public function getLeaderboard($limit)
    {
        $data = $this->repo->getGlobalLeaderboard($limit);

        return $data->map(function ($player, $index) {
            return [
                'rank' => $index + 1,
                'username' => $player->username,
                'name' => $player->name,
                'total_score' => (int) $player->total_score,
                'total_games' => (int) $player->total_games,
                'avg_score' => round((float) $player->avg_score, 1)
            ];
        });
    }

    // Method tambahan untuk leaderboard per sesi (Endpoint khusus)
    public function getSessionLeaderboard($sessionId)
    {
        return \Illuminate\Support\Facades\DB::table('participatesin')
            ->join('players', 'participatesin.playerId', '=', 'players.PlayerId')
            ->where('participatesin.sessionId', $sessionId)
            ->select(
                'players.name',
                'players.PlayerId as player_id',
                'participatesin.score',
                'participatesin.rank',
                'participatesin.position as tile_position',
                'participatesin.is_ready'
            )
            ->orderBy('participatesin.rank', 'asc')
            ->orderBy('participatesin.score', 'desc')
            ->get();
    }
}