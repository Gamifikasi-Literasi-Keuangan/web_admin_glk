<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminPlayerController;
use App\Http\Controllers\Admin\AdminScenarioController;
use App\Http\Controllers\Admin\AdminCardController;
use App\Http\Controllers\Admin\AdminTileController;
use App\Http\Controllers\Admin\AdminInterventionController;
use App\Http\Controllers\Admin\AdminConfigController;
use App\Http\Controllers\Admin\AdminSessionController;
use App\Http\Controllers\Admin\AdminLeaderboardController;
use App\Http\Controllers\Admin\AdminMetricController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminAnalyticsController;
use App\Http\Controllers\ANNController;
use App\Http\Controllers\Admin\AdminTrainingDataController;
use App\Http\Controllers\Admin\AdminProfilingQuestionController;

Route::get('/scenario/{scenario}', [ScenarioController::class, 'show']);
Route::post('/scenario/submit', [ScenarioController::class, 'submit']);
Route::post('/feedback/intervention', [FeedbackController::class, 'store']);
Route::get('/intervention/trigger', [InterventionController::class, 'trigger']);
Route::get('/leaderboard', [LeaderboardController::class, 'getLeaderboard']);


Route::prefix('admin')->group(function () {
    Route::post('/auth/login', [AdminAuthController::class, 'login']);

});
Route::middleware(['auth:api', 'role:admin'])->prefix('admin')->group(function () {
    Route::post('/auth/logout', [AdminAuthController::class, 'logout']);
    // Paket 1: Players
    Route::get('/players', [AdminPlayerController::class, 'index']);
    Route::get('/players/{id}', [AdminPlayerController::class, 'show']);
    Route::get('/players/{id}/analysis', [AdminPlayerController::class, 'analysis']);
    Route::delete('/players/{id}', [AdminPlayerController::class, 'destroy']);
    Route::post('/players/{id}/ban', [AdminPlayerController::class, 'ban']);
    Route::post('/players/{id}/unban', [AdminPlayerController::class, 'unban']);
    // Paket 2: Content Management
    Route::get('/scenarios', [AdminScenarioController::class, 'index']);
    Route::get('/scenarios/{id}', [AdminScenarioController::class, 'show']);
    Route::post('/scenarios', [AdminScenarioController::class, 'store']);
    Route::put('/scenarios/{id}', [AdminScenarioController::class, 'update']);
    Route::delete('/scenarios/{id}', [AdminScenarioController::class, 'destroy']);
    Route::get('/tiles', [AdminTileController::class, 'index']);
    Route::get('/tiles/{id}', [AdminTileController::class, 'show']);
    Route::post('/tiles', [AdminTileController::class, 'store']);
    Route::put('/tiles/{id}', [AdminTileController::class, 'update']);
    Route::get('/tiles/meta/contents', [AdminTileController::class, 'contents']);
    Route::get('/interventions', [AdminInterventionController::class, 'index']);
    Route::get('/interventions/{id}', [AdminInterventionController::class, 'show']);
    Route::post('/interventions', [AdminInterventionController::class, 'store']);
    Route::put('/interventions/{id}', [AdminInterventionController::class, 'update']);
    Route::delete('/interventions/{id}', [AdminInterventionController::class, 'destroy']);
    Route::get('/config/game', [AdminConfigController::class, 'show']);
    Route::put('/config/game', [AdminConfigController::class, 'update']);
    Route::prefix('cards')->group(function () {
        // Risk
        Route::get('/risk', [AdminCardController::class, 'indexRisk']);
        Route::get('/risk/{id}', [AdminCardController::class, 'showRisk']);
        Route::post('/risk', [AdminCardController::class, 'storeRisk']);
        Route::put('/risk/{id}', [AdminCardController::class, 'updateRisk']);
        Route::delete('/risk/{id}', [AdminCardController::class, 'destroyRisk']);
        // Chance
        Route::get('/chance', [AdminCardController::class, 'indexChance']);
        Route::get('/chance/{id}', [AdminCardController::class, 'showChance']);
        Route::post('/chance', [AdminCardController::class, 'storeChance']);
        Route::put('/chance/{id}', [AdminCardController::class, 'updateChance']);
        Route::delete('/chance/{id}', [AdminCardController::class, 'destroyChance']);
        // Quiz
        Route::get('/quiz', [AdminCardController::class, 'indexQuiz']);
        Route::get('/quiz/{id}', [AdminCardController::class, 'showQuiz']);
        Route::post('/quiz', [AdminCardController::class, 'storeQuiz']);
        Route::put('/quiz/{id}', [AdminCardController::class, 'updateQuiz']);
        Route::delete('/quiz/{id}', [AdminCardController::class, 'destroyQuiz']);
    });
    Route::get('/sessions', [AdminSessionController::class, 'index']);
    //pake 3: Leaderboard dan Session Detail
    Route::get('/sessions/{id}', [AdminSessionController::class, 'show']);
    Route::get('/leaderboard/global', [AdminLeaderboardController::class, 'globalLeaderboard']);
    Route::get('/sessions/{id}/leaderboard', [AdminSessionController::class, 'leaderboard']);

    Route::get('/reports/outcomes', [AdminReportController::class, 'learningOutcomes']);
    Route::prefix('analytics')->group(function () {
        Route::get('/overview', [AdminAnalyticsController::class, 'overview']);
        Route::get('/learning-curve', [AdminAnalyticsController::class, 'learningCurve']);
        Route::get('/skill-matrix', [AdminAnalyticsController::class, 'skillMatrix']);
        Route::get('/mastery', [AdminAnalyticsController::class, 'masteryDistribution']);
        Route::get('/difficulty', [AdminAnalyticsController::class, 'difficultyAnalysis']);
        Route::get('/decisions', [AdminAnalyticsController::class, 'decisionPatterns']);
        Route::get('/mistakes', [AdminAnalyticsController::class, 'mistakePatterns']);
        Route::get('/interventions', [AdminAnalyticsController::class, 'interventionSummary']);
        Route::get('/scenarios', [AdminAnalyticsController::class, 'scenarioEffectiveness']);
        Route::get('/cards', [AdminAnalyticsController::class, 'cardImpact']);
        Route::get('/quizzes', [AdminAnalyticsController::class, 'quizPerformance']);
        Route::get('/heatmap/tiles', [AdminAnalyticsController::class, 'tileHeatmap']);
        Route::get('/heatmap/time', [AdminAnalyticsController::class, 'timeHeatmap']);
        Route::get('/distribution', [AdminAnalyticsController::class, 'scoreDistribution']);
        Route::get('/funnel', [AdminAnalyticsController::class, 'funnel']);
    });
    Route::prefix('metrics')->group(function () {
        Route::get('/kpi', [AdminMetricController::class, 'kpi']);
        Route::get('/growth', [AdminMetricController::class, 'growthMetrics']);
        Route::get('/engagement', [AdminMetricController::class, 'engagement']);
    });
    
    // ANN Management Endpoints
    Route::prefix('ann')->group(function () {
        Route::post('/train', [ANNController::class, 'train']);
        Route::post('/test', [ANNController::class, 'test']);
        Route::get('/evaluate', [ANNController::class, 'evaluate']);
    });

    // Training Data CRUD
    Route::prefix('training-data')->group(function () {
        Route::get('/', [AdminTrainingDataController::class, 'index']);
        Route::post('/', [AdminTrainingDataController::class, 'store']);
        Route::get('/options', [AdminTrainingDataController::class, 'options']);
        Route::get('/{id}', [AdminTrainingDataController::class, 'show']);
        Route::put('/{id}', [AdminTrainingDataController::class, 'update']);
        Route::delete('/{id}', [AdminTrainingDataController::class, 'destroy']);
        Route::post('/bulk-delete', [AdminTrainingDataController::class, 'bulkDelete']);
    });

    // Profiling Questions CRUD
    Route::prefix('profiling-questions')->group(function () {
        Route::get('/aspects', [AdminProfilingQuestionController::class, 'getAspects']);
        Route::get('/', [AdminProfilingQuestionController::class, 'index']);
        Route::post('/', [AdminProfilingQuestionController::class, 'store']);
        Route::get('/{id}', [AdminProfilingQuestionController::class, 'show']);
        Route::put('/{id}', [AdminProfilingQuestionController::class, 'update']);
        Route::delete('/{id}', [AdminProfilingQuestionController::class, 'destroy']);
        Route::post('/{id}/toggle-active', [AdminProfilingQuestionController::class, 'toggleActive']);
    });
});