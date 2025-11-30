<?php
namespace App\Repositories;

use App\Models\GameSession;
use Illuminate\Support\Facades\DB;

class SessionRepository
{
    public function getPaginated($limit, $filters = [])
    {
        $query = DB::table('game_sessions')
            ->leftJoin('players', 'game_sessions.host_player_id', '=', 'players.PlayerId')
            // Cari pemenang (Rank 1)
            ->leftJoin('ParticipatesIn', function($join) {
                $join->on('game_sessions.sessionId', '=', 'ParticipatesIn.sessionId')
                     ->where('ParticipatesIn.rank', '=', 1);
            })
            ->leftJoin('players as winner', 'ParticipatesIn.playerId', '=', 'winner.PlayerId')
            ->select(
                'game_sessions.*',
                'players.name as host_name',
                'winner.name as winner_name',
                'ParticipatesIn.score as winning_score'
            )
            ->orderBy('game_sessions.created_at', 'desc');

        if (!empty($filters['status'])) {
            $query->where('game_sessions.status', $filters['status']);
        }
        
        if (!empty($filters['date_from'])) {
            $query->whereDate('game_sessions.created_at', '>=', $filters['date_from']);
        }

        return $query->paginate($limit);
    }

    public function findById($sessionId)
    {
        return GameSession::with(['host', 'participants.player', 'turns.player'])
            ->where('sessionId', $sessionId)
            ->first();
    }

    public function getGlobalLeaderboard($limit)
    {
        return DB::table('ParticipatesIn')
            ->join('players', 'ParticipatesIn.playerId', '=', 'players.PlayerId')
            ->join('auth_users', 'players.user_id', '=', 'auth_users.id')
            ->select(
                'players.PlayerId', 'players.name', 'auth_users.username',
                DB::raw('SUM(score) as total_score'),
                DB::raw('COUNT(ParticipatesIn.id) as total_games'),
                DB::raw('AVG(score) as avg_score')
            )
            ->groupBy('players.PlayerId', 'players.name', 'auth_users.username')
            ->orderBy('total_score', 'desc')
            ->limit($limit)
            ->get();
    }
}