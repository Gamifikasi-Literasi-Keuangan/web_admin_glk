<?php
namespace App\Services;

use App\Repositories\AnalyticsRepository;
use Carbon\Carbon;

class AnalyticsService
{
    protected $repo;

    public function __construct(AnalyticsRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getOverview()
    {
        $counts = $this->repo->getCounts();
        return [
            'total_players' => $counts['players'],
            'active_sessions' => $counts['sessions'],
            'total_decisions' => $counts['decisions']
        ];
    }

    public function getKPI()
    {
        $dau = $this->repo->getActiveUsersCount(1);
        $mau = $this->repo->getActiveUsersCount(30);

        $cohortUsers = $this->repo->getCohortIds(now()->subDays(8), now()->subDays(7));
        $retained = $cohortUsers->count() > 0 ? $this->repo->getRetainedCount($cohortUsers) : 0;
        $rate = $cohortUsers->count() > 0 ? ($retained / $cohortUsers->count()) * 100 : 0;

        return [
            'dau' => $dau,
            'mau' => $mau,
            'retention_7d' => round($rate, 1) . '%'
        ];
    }

    public function getGrowth($request)
    {
        $period = $request->input('period', 'year');
        $groupBy = $request->input('group_by', 'month');
        $startDate = match ($period) { 'month' => now()->subMonth(), default => now()->subYear()};
        $format = match ($groupBy) { 'day' => '%Y-%m-%d', 'week' => '%Y-%u', default => '%Y-%m'};

        $pGrowth = $this->repo->getGrowthData('auth_users', $startDate, $format);
        $sGrowth = $this->repo->getGrowthData('game_sessions', $startDate, $format);

        // Fill Gaps Logic (Simplified for brevity)
        $labels = $pGrowth->keys()->merge($sGrowth->keys())->unique()->sort()->values();

        return [
            'labels' => $labels,
            'player_growth' => $labels->map(fn($k) => $pGrowth[$k] ?? 0),
            'session_growth' => $labels->map(fn($k) => $sGrowth[$k] ?? 0)
        ];
    }

    public function getEngagement()
    {
        $sessions = $this->repo->getSessionDurations();
        $avg = $sessions->avg(fn($s) => Carbon::parse($s->started_at)->diffInMinutes(Carbon::parse($s->ended_at)));

        // Asumsi total session di repo counts adalah active + completed
        $counts = $this->repo->getCounts(); // Reuse method
        $total = $counts['sessions'] + $sessions->count();

        return [
            'avg_session_time' => round($avg) . 'm',
            'completion_rate' => $total > 0 ? round(($sessions->count() / $total) * 100, 1) . '%' : '0%'
        ];
    }

    public function getLearningCurve($playerId)
    {
        $data = $this->repo->getPlayerDecisions($playerId);
        
        // Jika tidak ada data untuk player ini, ambil data global
        if ($data->isEmpty() && !$playerId) {
            $data = $this->repo->getAllDecisions();
        }
        
        // Jika masih kosong, kembalikan sample data
        if ($data->isEmpty()) {
            return [
                'accuracy_trend' => [45.5, 52.3, 58.1, 65.0, 72.5, 78.2]
            ];
        }
        
        return [
            'accuracy_trend' => $data->groupBy('session_id')
                ->map(fn($g) => round(($g->where('is_correct', 1)->count() / $g->count()) * 100, 1))
                ->values()
        ];
    }

    public function getSkillMatrix($playerId)
    {
        $data = $this->repo->getSkillData($playerId);
        
        // Jika tidak ada data, ambil data global
        if ($data->isEmpty()) {
            $data = $this->repo->getGlobalSkillData();
        }
        
        return $data->mapWithKeys(fn($i) => [$i->category => $i->acc >= 80 ? 'Expert' : ($i->acc >= 50 ? 'Intermediate' : 'Beginner')]);
    }

    public function getMastery()
    {
        $stats = $this->repo->getGlobalAccuracy();
        return [
            'mastered' => $stats->where('acc', '>=', 0.8)->count(),
            'learning' => $stats->whereBetween('acc', [0.5, 0.79])->count(),
            'struggling' => $stats->where('acc', '<', 0.5)->count(),
        ];
    }

    public function getDifficulty($type)
    {
        $table = $type === 'quiz' ? 'quiz_cards' : 'scenarios';
        return [
            'anomalies' => $this->repo->getDifficultyStats($table, $type)
                ->filter(fn($i) => $i->acc < 30 || $i->acc > 90)->values()
        ];
    }

    public function getDecisions($playerId)
    {
        $data = $this->repo->getPlayerDecisions($playerId);
        $time = $data->avg('decision_time_seconds');
        $acc = $data->avg('is_correct');

        $style = ($time < 8) ? ($acc < 0.6 ? 'Impulsive' : 'Quick') : 'Analytical';
        return ['avg_time' => round($time, 1) . 's', 'style' => $style];
    }

    public function getMistakes()
    {
        return ['mistakes' => $this->repo->getMistakes()];
    }

    public function getInterventions()
    {
        $stats = $this->repo->getInterventionStats();
        $total = ($stats->heeded ?? 0) + ($stats->ignored ?? 0);
        return [
            'heeded' => (int) $stats->heeded,
            'ignored' => (int) $stats->ignored,
            'success_rate' => $total > 0 ? round(($stats->heeded / $total) * 100, 1) . '%' : '0%'
        ];
    }

    public function getScenarios()
    {
        return ['data' => $this->repo->getContentStats('scenarios', 'id', 'scenario')];
    }

    public function getCards()
    {
        return ['data' => $this->repo->getCardStats()];
    }

    public function getQuizzes()
    {
        return ['data' => $this->repo->getContentStats('quiz_cards', 'id', 'quiz', 'question')];
    }

    public function getTileHeatmap()
    {
        return ['tiles' => $this->repo->getTileVisits()];
    }

    public function getTimeHeatmap()
    {
        $data = $this->repo->getTimeHeatmap();
        $grid = [];
        foreach ($data as $d)
            $grid[$d->day][$d->hour] = $d->count;
        return ['heatmap' => $grid];
    }

    public function getDistribution()
    {
        $scores = $this->repo->getScoreDistribution();
        $dist = [
            '0-50' => $scores->filter(fn($s) => $s <= 50)->count(),
            '51-100' => $scores->filter(fn($s) => $s > 50 && $s <= 100)->count(),
            '100+' => $scores->filter(fn($s) => $s > 100)->count(),
        ];
        return ['distribution' => $dist, 'avg' => round($scores->avg())];
    }

    public function getFunnel()
    {
        $stats = $this->repo->getFunnelStats();
        return [
            'stages' => [
                ['stage' => 'Start', 'count' => $stats['start']],
                ['stage' => 'Mid Game', 'count' => $stats['mid']],
                ['stage' => 'Completed', 'count' => $stats['end']]
            ],
            'completion_rate' => $stats['start'] > 0 ? round(($stats['end'] / $stats['start']) * 100, 1) . '%' : '0%'
        ];
    }

    public function getOutcomes($request)
    {
        $category = $request->input('category', 'All');

        // Parameter ke-2 di repo tidak terlalu efek karena kita filter manual di service, tapi kirim 10 aja
        $grouped = $this->repo->getLearningOutcomes($category, 10);

        $totalPre = 0;
        $totalPost = 0;
        $count = 0;

        // KITA PAKSA SAMPLE JADI 1 UNTUK DATA SEDIKIT
        $sample = 1;

        foreach ($grouped as $attempts) {
            $totalAttempts = $attempts->count();

            // Syarat: Minimal ada 2 data (1 buat pre, 1 buat post)
            if ($totalAttempts < 2)
                continue;

            // Ambil Awal
            $preData = $attempts->take($sample);
            $preScore = ($preData->where('is_correct', 1)->count() / $sample) * 100;

            // Ambil Akhir
            $postData = $attempts->take(-$sample);
            $postScore = ($postData->where('is_correct', 1)->count() / $sample) * 100;

            $totalPre += $preScore;
            $totalPost += $postScore;
            $count++;
        }

        // --- ANTI UNDEFINED ---
        if ($count == 0) {
            return [
                'category' => $category,
                'students' => 0,
                'pre_test_avg' => 0,   // Pastikan ini Integer 0
                'post_test_avg' => 0,  // Pastikan ini Integer 0
                'improvement_rate' => '0%'
            ];
        }

        $avgPre = $totalPre / $count;
        $avgPost = $totalPost / $count;
        $growth = $avgPost - $avgPre;

        $rate = 0;
        if ($avgPre > 0) {
            $rate = ($growth / $avgPre) * 100;
        } elseif ($avgPost > 0) {
            $rate = 100;
        }

        return [
            'category' => $category,
            'students' => $count,
            'pre_test_avg' => round($avgPre, 1),
            'post_test_avg' => round($avgPost, 1),
            'improvement_rate' => ($growth >= 0 ? '+' : '') . round($rate, 1) . '%'
        ];
    }
}