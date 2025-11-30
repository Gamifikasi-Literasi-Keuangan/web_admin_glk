<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AdminAnalyticsController extends Controller
{
    protected $service;
    public function __construct(AnalyticsService $service) { $this->service = $service; }

    public function overview() { return response()->json($this->service->getOverview()); }
    public function learningCurve(Request $r) { return response()->json($this->service->getLearningCurve($r->player_id)); }
    public function skillMatrix(Request $r) { return response()->json($this->service->getSkillMatrix($r->player_id)); }
    public function masteryDistribution(Request $r) { return response()->json($this->service->getMastery()); }
    public function difficultyAnalysis(Request $r) { return response()->json($this->service->getDifficulty($r->input('type', 'scenario'))); }
    public function decisionPatterns(Request $r) { return response()->json($this->service->getDecisions($r->player_id)); }
    public function mistakePatterns(Request $r) { return response()->json($this->service->getMistakes()); }
    public function interventionSummary() { return response()->json($this->service->getInterventions()); }
    public function scenarioEffectiveness() { return response()->json($this->service->getScenarios()); }
    public function cardImpact() { return response()->json($this->service->getCards()); }
    public function quizPerformance() { return response()->json($this->service->getQuizzes()); }
    public function tileHeatmap() { return response()->json($this->service->getTileHeatmap()); }
    public function timeHeatmap() { return response()->json($this->service->getTimeHeatmap()); }
    public function scoreDistribution() { return response()->json($this->service->getDistribution()); }
    public function funnel() { return response()->json($this->service->getFunnel()); }
}