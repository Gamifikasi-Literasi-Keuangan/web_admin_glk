<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function learningOutcomes(Request $request)
    {
        $category = $request->input('category', 'All');
        $sample = 10;

        $query = DB::table('player_decisions')
            ->where('content_type', 'scenario')
            ->select('player_id', 'is_correct', 'created_at');

        if ($category !== 'All') {
            $query->join('scenarios', 'player_decisions.content_id', '=', 'scenarios.id')
                  ->where('scenarios.category', $category);
        }

        $grouped = $query->orderBy('created_at')->get()->groupBy('player_id');
        
        $totalPre = 0; $totalPost = 0; $count = 0;

        foreach ($grouped as $attempts) {
            if ($attempts->count() < ($sample * 2)) continue;

            $pre = $attempts->take($sample)->where('is_correct', 1)->count();
            $post = $attempts->take(-$sample)->where('is_correct', 1)->count();
            
            $totalPre += ($pre / $sample) * 100;
            $totalPost += ($post / $sample) * 100;
            $count++;
        }

        if ($count == 0) return response()->json(['message' => 'Not enough data']);

        $avgPre = $totalPre / $count;
        $avgPost = $totalPost / $count;
        $growth = $avgPost - $avgPre;

        return response()->json([
            'category' => $category,
            'students' => $count,
            'pre_test_avg' => round($avgPre, 1),
            'post_test_avg' => round($avgPost, 1),
            'improvement_rate' => ($growth >= 0 ? '+' : '') . round(($avgPre > 0 ? ($growth/$avgPre)*100 : 0), 1) . '%'
        ]);
    }
}