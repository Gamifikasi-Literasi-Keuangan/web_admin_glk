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
                    'category' => $tpl->category,
                    'title' => $tpl->title_template,
                    'message' => $tpl->message_template,
                    'heed_message' => $tpl->heed_message,
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
            $template = DB::table('interventiontemplates')->where('id', $id)->first();

            if (!$template) {
                return response()->json(['error' => 'Intervensi tidak ditemukan'], 404);
            }

            return response()->json([
                'data' => [
                    'id' => $template->id,
                    'level_id' => $template->level,
                    'risk_label' => $template->risk_level,
                    'category' => $template->category,
                    'title' => $template->title_template,
                    'message' => $template->message_template,
                    'heed_message' => $template->heed_message,
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
            'category' => 'required|in:pendapatan,anggaran,tabungan_dan_dana_darurat,utang,investasi,asuransi,tujuan_jangka_panjang',
            'title_template' => 'required|string|max:255',
            'message_template' => 'required|string',
            'heed_message' => 'nullable|string',
            'actions' => 'required|array|min:1',
            'is_mandatory' => 'boolean'
        ]);

        DB::table('interventiontemplates')->insert([
            'level' => $validated['level'],
            'risk_level' => $validated['risk_level'],
            'category' => $validated['category'],
            'title_template' => $validated['title_template'],
            'message_template' => $validated['message_template'],
            'heed_message' => $validated['heed_message'] ?? null,
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
        $template = DB::table('interventiontemplates')->where('id', $id)->first();

        if (!$template) {
            return response()->json(['error' => 'Intervensi tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'level' => 'required|integer|min:1|max:10',
            'risk_level' => 'required|in:Critical,High,Medium,Low',
            'category' => 'required|in:pendapatan,anggaran,tabungan_dan_dana_darurat,utang,investasi,asuransi,tujuan_jangka_panjang',
            'title_template' => 'required|string|max:255',
            'message_template' => 'required|string',
            'heed_message' => 'nullable|string',
            'actions' => 'required|array|min:1',
            'is_mandatory' => 'boolean'
        ]);

        DB::table('interventiontemplates')->where('id', $id)->update([
            'level' => $validated['level'],
            'risk_level' => $validated['risk_level'],
            'category' => $validated['category'],
            'title_template' => $validated['title_template'],
            'message_template' => $validated['message_template'],
            'heed_message' => $validated['heed_message'] ?? null,
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
        $deleted = DB::table('interventiontemplates')->where('id', $id)->delete();

        if (!$deleted) {
            return response()->json(['error' => 'Intervensi tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Intervensi berhasil dihapus'
        ]);
    }
}