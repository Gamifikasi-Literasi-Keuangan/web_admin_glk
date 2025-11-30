<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AdminMetricController extends Controller
{
    protected $service;
    public function __construct(AnalyticsService $service) { $this->service = $service; }

    public function kpi(Request $request) { return response()->json($this->service->getKPI()); }
    public function growthMetrics(Request $request) { return response()->json($this->service->getGrowth($request)); }
    public function engagement(Request $request) { return response()->json($this->service->getEngagement()); }
}