<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminInterventionController extends Controller
{
    // --- READ: Get All Interventions ---
    public function index()
    {
        try {
            $templates = DB::table('interventiontemplates')
                ->orderBy('level', 'asc')
                ->get();

            $formatted = $templates->map(function ($tpl) {
                return [
                    'id' => $tpl->id,
                    'level_id' => $tpl->level,
                    'risk_label' => $tpl->risk_level,
                    'title' => $tpl->title_template,
                    'message' => $tpl->message_template,
                    'actions' => $tpl->actions_template ? json_decode($tpl->actions_template, true) : [],
                    'is_mandatory' => (bool) $tpl->is_mandatory,
                    'ui_color' => match ($tpl->risk_level) {
                        'Critical' => 'red',
                        'High' => 'orange',
                        'Medium' => 'yellow',
                        default => 'blue'
                    }
                ];
            });

            return response()->json(['data' => $formatted]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil data intervensi',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // --- READ: Get Single Intervention ---
    public function show($id)
    {
        try {
            $template = DB::table('interventiontemplates')->find($id);

            if (!$template) {
                return response()->json(['error' => 'Intervensi tidak ditemukan'], 404);
            }

            return response()->json([
                'data' => [
                    'id' => $template->id,
                    'level_id' => $template->level,
                    'risk_label' => $template->risk_level,
                    'title' => $template->title_template,
                    'message' => $template->message_template,
                    'actions' => $template->actions_template ? json_decode($template->actions_template, true) : [],
                    'is_mandatory' => (bool) $template->is_mandatory,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil detail intervensi',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // --- CREATE: Store New Intervention ---
    public function store(Request $request)
    {
        $validated = $request->validate([
            'level' => 'required|integer|min:1|max:10',
            'risk_level' => 'required|in:Critical,High,Medium,Low',
            'title_template' => 'required|string|max:255',
            'message_template' => 'required|string',
            'actions' => 'required|array|min:1',
            'is_mandatory' => 'boolean'
        ]);

        DB::table('interventiontemplates')->insert([
            'level' => $validated['level'],
            'risk_level' => $validated['risk_level'],
            'title_template' => $validated['title_template'],
            'message_template' => $validated['message_template'],
            'actions_template' => json_encode($validated['actions']),
            'is_mandatory' => $validated['is_mandatory'] ?? false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Intervensi berhasil ditambahkan'
        ]);
    }

    // --- UPDATE: Update Intervention ---
    public function update(Request $request, $id)
    {
        $template = DB::table('interventiontemplates')->find($id);

        if (!$template) {
            return response()->json(['error' => 'Intervensi tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'level' => 'required|integer|min:1|max:10',
            'risk_level' => 'required|in:Critical,High,Medium,Low',
            'title_template' => 'required|string|max:255',
            'message_template' => 'required|string',
            'actions' => 'required|array|min:1',
            'is_mandatory' => 'boolean'
        ]);

        DB::table('interventiontemplates')->where('id', $id)->update([
            'level' => $validated['level'],
            'risk_level' => $validated['risk_level'],
            'title_template' => $validated['title_template'],
            'message_template' => $validated['message_template'],
            'actions_template' => json_encode($validated['actions']),
            'is_mandatory' => $validated['is_mandatory'] ?? false,
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Intervensi berhasil diperbarui'
        ]);
    }

    // --- DELETE: Delete Intervention ---
    public function destroy($id)
    {
        $template = DB::table('interventiontemplates')->find($id);

        if (!$template) {
            return response()->json(['error' => 'Intervensi tidak ditemukan'], 404);
        }

        DB::table('interventiontemplates')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Intervensi berhasil dihapus'
        ]);
    }
}