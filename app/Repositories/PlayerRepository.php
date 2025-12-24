<?php
namespace App\Repositories;

use App\Models\Player;
use Illuminate\Support\Facades\DB;

class PlayerRepository
{
    public function getPaginated($limit, $search = null, $sort = 'created_at', $dir = 'desc')
    {
        $query = Player::with(['user', 'profile']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%");
                });
        }

        // Mapping sorting agar aman
        $sortColumn = match ($sort) {
            'total_games' => 'gamesPlayed',
            default => 'created_at'
        };

        return $query->orderBy($sortColumn, $dir)->paginate($limit);
    }

    public function findById($id)
    {
        return Player::with(['user', 'profile'])->where('PlayerId', $id)->first();
    }

    // Mengambil raw data untuk analisis (Join ke table decisions)
    public function getAnalysisData($playerId)
    {
        return DB::table('player_decisions')
            ->where('player_id', $playerId)
            ->whereIn('content_type', ['scenario', 'quiz'])
            ->leftJoin('scenarios', function ($join) {
                $join->on('player_decisions.content_id', '=', 'scenarios.id')
                    ->where('player_decisions.content_type', '=', 'scenario');
            })
            ->select(
                DB::raw('COALESCE(scenarios.category, "General") as category'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(is_correct) as correct'),
                DB::raw('AVG(is_correct) * 100 as accuracy')
            )
            ->groupBy('category')
            ->get();
    }

    public function getPlayerStats($playerId)
    {
        return DB::table('participatesin')
            ->where('playerId', $playerId)
            ->selectRaw('COUNT(*) as total_games')
            ->selectRaw('AVG(score) as avg_score')
            ->selectRaw('SUM(CASE WHEN `rank` = 1 THEN 1 ELSE 0 END) as wins')
            ->first();
    }

    public function deletePlayer($playerId)
    {
        $player = $this->findById($playerId);
        if (!$player) {
            return false;
        }

        // Delete player dan related data
        DB::transaction(function () use ($player) {
            // Delete dari participatesin
            DB::table('participatesin')->where('playerId', $player->PlayerId)->delete();
            
            // Delete dari player_decisions
            DB::table('player_decisions')->where('player_id', $player->PlayerId)->delete();
            
            // Delete dari player_profile
            if ($player->profile) {
                $player->profile->delete();
            }
            
            // Delete player
            $player->delete();
        });

        return true;
    }

    public function banPlayer($playerId, $banReason = null)
    {
        $player = $this->findById($playerId);
        if (!$player || !$player->user) {
            return false;
        }

        // Update status banned di auth_users
        $player->user->update([
            'is_active' => false,
            'ban_reason' => $banReason ?? 'Banned by admin'
        ]);

        return true;
    }

    public function unbanPlayer($playerId)
    {
        $player = $this->findById($playerId);
        if (!$player || !$player->user) {
            return false;
        }

        // Update status active di auth_users
        $player->user->update([
            'is_active' => true,
            'ban_reason' => null
        ]);

        return true;
    }
}