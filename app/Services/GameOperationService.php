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
                'status' => $session->status, // Keep lowercase untuk consistency dengan JS
                'host' => $session->host_name ?? 'Unknown',
                'winner' => $session->winner_name ?? '-',
                'winning_score' => (int) ($session->winning_score ?? 0),
                'player_count' => (int) ($session->player_count ?? 0),
                'duration_human' => $duration > 0 ? gmdate("H:i:s", $duration) : '-',
                'created_at' => $session->created_at,
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
        } elseif ($session->started_at && $session->status === 'active') {
            // Jika masih active, hitung dari started_at sampai sekarang
            $minutes = abs(\Carbon\Carbon::now()->diffInMinutes($session->started_at));
            $duration = $minutes . 'm';
        }

        // Get current player info dari game_sessions.current_player_id
        $currentPlayer = null;
        if ($session->current_player_id && $session->status === 'active') {
            $player = \App\Models\Player::where('PlayerId', $session->current_player_id)->first();
            if ($player) {
                $currentPlayer = [
                    'player_id' => $player->PlayerId,
                    'player_name' => $player->name,
                    'turn_number' => $session->current_turn ?? 0
                ];
            }
        }

        // Format Timeline dari Turns
        $timeline = $session->turns->map(function ($turn) {
            return [
                'turn_number' => $turn->turn_number,
                'player' => $turn->player->name ?? 'Unknown Player',
                'player_id' => $turn->player_id ?? null,
                'timestamp' => $turn->started_at ? $turn->started_at->format('H:i:s') : '-',
                // Disini nanti bisa dikembangkan ambil detail activity dari player_decisions
                'activity' => [
                    'dice_roll' => null, // Atau ambil dari telemetry jika ada
                    'decisions' => []    // Atau ambil dari player_decisions jika ada
                ]
            ];
        });

        // Hitung player count dari participants
        $playerCount = $session->participants->count();

        // PERBAIKAN STRUKTUR DATA DI SINI
        return [
            'session_info' => [
                'id' => $session->sessionId,
                'status' => $session->status, // Kirim lowercase agar consistent dengan DB
                // Gunakan null coalescing (??) agar tidak error jika host terhapus
                'host' => $session->host->name ?? 'Unknown Host',
                'host_id' => $session->host_player_id ?? null,
                'created_at' => $session->created_at ? $session->created_at->toDateTimeString() : '-',
                'started_at' => $session->started_at ? $session->started_at->toDateTimeString() : null,
                'ended_at' => $session->ended_at ? $session->ended_at->toDateTimeString() : null,
                'duration' => $duration,
                'total_turns' => $session->current_turn ?? 0,
                'player_count' => $playerCount,
                'max_players' => $session->max_players ?? 5,
            ],
            'current_player' => $currentPlayer, // Tambahkan info current player
            'leaderboard' => $session->participants->map(function ($p) {
                return [
                    'player_id' => $p->playerId ?? null, // Tambahkan player_id
                    'name' => $p->player->name ?? 'Unknown',
                    'score' => (int) $p->score,
                    'rank' => (int) $p->rank,
                    'final_tile_position' => (int) $p->position,
                    'connection_status' => $p->connection_status,
                    'is_ready' => (bool) $p->is_ready
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

    // Force end an active session
    public function forceEndSession($sessionId)
    {
        $session = $this->repo->findById($sessionId);
        if (!$session) {
            return false;
        }

        // Only end active or waiting sessions
        if (!in_array($session->status, ['active', 'waiting'])) {
            return false;
        }

        $session->status = 'completed';
        $session->ended_at = now();
        $session->save();

        return true;
    }

    // Delete a session (only completed or waiting sessions)
    public function deleteSession($sessionId)
    {
        $session = $this->repo->findById($sessionId);
        if (!$session) {
            return false;
        }

        // Only delete completed or waiting sessions, not active ones
        if ($session->status === 'active') {
            return false;
        }

        // Delete related data first
        \Illuminate\Support\Facades\DB::transaction(function () use ($session) {
            // Delete participants
            \Illuminate\Support\Facades\DB::table('participatesin')
                ->where('sessionId', $session->sessionId)
                ->delete();

            // Delete turns
            \Illuminate\Support\Facades\DB::table('turns')
                ->where('session_id', $session->sessionId)
                ->delete();

            // Delete session
            $session->delete();
        });

        return true;
    }
}