<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsRepository
{
    // --- DASHBOARD & METRICS ---
    public function getCounts()
    {
        return [
            'players' => DB::table('players')->count(),
            'sessions' => DB::table('game_sessions')->where('status', 'active')->count(),
            'decisions' => DB::table('player_decisions')->count()
        ];
    }

    public function getActiveUsersCount($days)
    {
        return DB::table('player_decisions')
            ->where('created_at', '>=', now()->subDays($days))
            ->distinct('player_id')->count('player_id');
    }

    public function getCohortIds($start, $end)
    {
        return DB::table('players')->whereBetween('created_at', [$start, $end])->pluck('PlayerId');
    }

    public function getRetainedCount($userIds)
    {
        return DB::table('player_decisions')
            ->whereIn('player_id', $userIds)
            ->where('created_at', '>=', now()->subDay())
            ->distinct('player_id')->count('player_id');
    }

    public function getGrowthData($table, $startDate, $format)
    {
        return DB::table($table)
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw("DATE_FORMAT(created_at, '{$format}') as time"), DB::raw('COUNT(*) as count'))
            ->groupBy('time')->pluck('count', 'time');
    }

    public function getSessionDurations()
    {
        return DB::table('game_sessions')
            ->where('status', 'completed')
            ->whereNotNull('ended_at')
            ->get();
    }

    // --- LEARNING & BEHAVIOR ---
    public function getPlayerDecisions($playerId)
    {
        return DB::table('player_decisions')
            ->where('player_id', $playerId)
            ->orderBy('created_at')
            ->get();
    }

    public function getAllDecisions()
    {
        return DB::table('player_decisions')
            ->orderBy('created_at')
            ->limit(500)
            ->get();
    }

    public function getSkillData($playerId)
    {
        return DB::table('player_decisions')
            ->join('scenarios', 'player_decisions.content_id', '=', 'scenarios.id')
            ->where('player_decisions.player_id', $playerId)
            ->select('scenarios.category', DB::raw('AVG(is_correct)*100 as acc'))
            ->groupBy('category')->get();
    }

    public function getGlobalSkillData()
    {
        return DB::table('player_decisions')
            ->join('scenarios', 'player_decisions.content_id', '=', 'scenarios.id')
            ->where('player_decisions.content_type', 'scenario')
            ->select('scenarios.category', DB::raw('AVG(is_correct)*100 as acc'))
            ->groupBy('category')->get();
    }

    public function getGlobalAccuracy()
    {
        return DB::table('player_decisions')
            ->select('player_id', DB::raw('AVG(is_correct) as acc'))
            ->groupBy('player_id')->get();
    }

    public function getDifficultyStats($table, $type)
    {
        return DB::table('player_decisions')
            ->join($table, 'player_decisions.content_id', '=', "$table.id")
            ->where('content_type', $type)
            ->select("$table.id", "$table.title", DB::raw('AVG(is_correct)*100 as acc'))
            ->groupBy("$table.id", "$table.title")
            ->get();
    }

    public function getMistakes()
    {
        return DB::table('player_decisions')
            ->join('scenarios', 'player_decisions.content_id', '=', 'scenarios.id')
            ->join('scenario_options', function ($join) {
                $join->on('player_decisions.content_id', '=', 'scenario_options.scenarioId')
                    ->on('player_decisions.selected_option', '=', 'scenario_options.optionId');
            })
            ->where('player_decisions.is_correct', 0) // <--- FIXED: Spesifik tabel
            ->select('scenarios.title', 'scenario_options.text', DB::raw('count(*) as count'))
            ->groupBy('scenarios.title', 'scenario_options.text')
            ->orderByDesc('count')->limit(5)->get();
    }

    public function getInterventionStats()
    {
        return DB::table('player_decisions')->where('intervention_triggered', 1)
            ->selectRaw('SUM(player_response="heeded") as heeded, SUM(player_response="ignored") as ignored')
            ->first();
    }

    // --- CONTENT ---
    public function getContentStats($table, $joinCol, $type, $titleCol = 'title')
    {
        return DB::table($table)
            ->leftJoin('player_decisions', function ($j) use ($table, $joinCol, $type) {
                $j->on("$table.id", '=', 'player_decisions.content_id')->where('content_type', $type);
            })
            // Gunakan $titleCol dinamis
            ->select("$table.$titleCol as title", DB::raw('AVG(player_decisions.is_correct)*100 as acc'), DB::raw('COUNT(player_decisions.id) as usage_count'))
            ->groupBy("$table.id", "$table.$titleCol")
            ->get();
    }

    public function getCardStats()
    {
        return DB::table('cards')
            ->leftJoin('player_decisions', 'cards.id', '=', 'player_decisions.content_id')
            ->select('cards.title', DB::raw('AVG(player_decisions.score_change) as impact'), DB::raw('COUNT(player_decisions.id) as freq'))
            ->groupBy('cards.id', 'cards.title')
            ->orderByDesc('freq')->get();
    }

    // --- VISUAL & FUNNEL ---
    public function getTileVisits()
    {
        return DB::table('telemetry')->where('action', 'landed')
            ->select('tile_id', DB::raw('count(*) as visits'))
            ->groupBy('tile_id')->get();
    }

    public function getTimeHeatmap()
    {
        return DB::table('player_decisions')
            ->select(DB::raw('DAYNAME(created_at) as day'), DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
            ->groupBy('day', 'hour')->get();
    }

    public function getScoreDistribution()
    {
        return DB::table('participatesin')->pluck('score');
    }

    public function getFunnelStats()
    {
        return [
            'start' => DB::table('game_sessions')->count(),
            'mid' => DB::table('game_sessions')->where('current_turn', '>', 15)->count(),
            'end' => DB::table('game_sessions')->where('status', 'completed')->count()
        ];
    }

    public function getLearningOutcomes($category, $sample)
    {
        $query = DB::table('player_decisions')
            ->where('content_type', 'scenario')
            ->select('player_id', 'is_correct', 'created_at');

        if ($category !== 'All') {
            $query->join('scenarios', 'player_decisions.content_id', '=', 'scenarios.id')
                ->where('scenarios.category', $category);
        }

        return $query->orderBy('created_at')->get()->groupBy('player_id');
    }
}