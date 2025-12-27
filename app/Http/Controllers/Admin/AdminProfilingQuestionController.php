<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilingQuestion;
use App\Models\ProfilingQuestionOption;
use App\Models\FinancialAspect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AdminProfilingQuestionController extends Controller
{
    /**
     * Display a listing of profiling questions
     */
    public function index(Request $request)
    {
        try {
            $query = ProfilingQuestion::with(['options', 'aspects']);

            // Filter by active status
            if ($request->has('is_active')) {
                if ($request->is_active == '1' || $request->is_active === true) {
                    $query->active();
                } else {
                    $query->where('is_active', false);
                }
            }

            // Search by question text or code
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('question_text', 'like', "%{$search}%")
                      ->orWhere('question_code', 'like', "%{$search}%");
                });
            }

            $perPage = $request->get('per_page', 15);
            $data = $query->latest()->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pertanyaan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified profiling question
     */
    public function show(string $id)
    {
        try {
            $question = ProfilingQuestion::with(['options', 'aspects'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $question
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store a newly created profiling question
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'question_code' => 'required|string|max:50|unique:profiling_questions,question_code',
                'question_text' => 'required|string',
                'max_score' => 'required|integer|min:1',
                'is_active' => 'nullable|boolean',
                'aspect_ids' => 'nullable|array',
                'aspect_ids.*' => 'exists:financial_aspects,id',
                'options' => 'required|array|min:2',
                'options.*.option_code' => 'nullable|string|max:5',
                'options.*.option_text' => 'required|string',
                'options.*.score' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Create question
            $question = ProfilingQuestion::create([
                'question_code' => $request->question_code,
                'question_text' => $request->question_text,
                'max_score' => $request->max_score,
                'is_active' => $request->is_active ?? true
            ]);

            // Create options
            if ($request->has('options')) {
                foreach ($request->options as $index => $option) {
                    ProfilingQuestionOption::create([
                        'question_id' => $question->id,
                        'option_code' => $option['option_code'] ?? chr(65 + $index), // A, B, C...
                        'option_text' => $option['option_text'],
                        'score' => $option['score']
                    ]);
                }
            }

            // Attach aspects
            if ($request->has('aspect_ids') && is_array($request->aspect_ids)) {
                $question->aspects()->attach($request->aspect_ids);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pertanyaan berhasil ditambahkan',
                'data' => $question->load(['options', 'aspects'])
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan pertanyaan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified profiling question
     */
    public function update(Request $request, string $id)
    {
        try {
            $question = ProfilingQuestion::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'question_code' => 'sometimes|string|max:50|unique:profiling_questions,question_code,' . $id,
                'question_text' => 'sometimes|string',
                'max_score' => 'sometimes|integer|min:1',
                'is_active' => 'nullable|boolean',
                'aspect_ids' => 'nullable|array',
                'aspect_ids.*' => 'exists:financial_aspects,id',
                'options' => 'sometimes|array|min:2',
                'options.*.id' => 'sometimes|exists:profiling_question_options,id',
                'options.*.option_code' => 'nullable|string|max:5',
                'options.*.option_text' => 'required|string',
                'options.*.score' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Update question
            $question->update($request->only(['question_code', 'question_text', 'max_score', 'is_active']));

            // Update options
            if ($request->has('options')) {
                // Delete old options that are not in the new list
                $newOptionIds = collect($request->options)->pluck('id')->filter();
                $question->options()->whereNotIn('id', $newOptionIds)->delete();

                // Update or create options
                foreach ($request->options as $index => $option) {
                    if (isset($option['id'])) {
                        // Update existing
                        ProfilingQuestionOption::where('id', $option['id'])
                            ->where('question_id', $question->id)
                            ->update([
                                'option_code' => $option['option_code'] ?? chr(65 + $index),
                                'option_text' => $option['option_text'],
                                'score' => $option['score']
                            ]);
                    } else {
                        // Create new
                        ProfilingQuestionOption::create([
                            'question_id' => $question->id,
                            'option_code' => $option['option_code'] ?? chr(65 + $index),
                            'option_text' => $option['option_text'],
                            'score' => $option['score']
                        ]);
                    }
                }
            }

            // Update aspects
            if ($request->has('aspect_ids')) {
                $question->aspects()->sync($request->aspect_ids);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pertanyaan berhasil diupdate',
                'data' => $question->load(['options', 'aspects'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate pertanyaan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified profiling question
     */
    public function destroy(string $id)
    {
        try {
            $question = ProfilingQuestion::findOrFail($id);
            $question->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pertanyaan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pertanyaan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all financial aspects for form options
     */
    public function getAspects()
    {
        try {
            $aspects = FinancialAspect::all();

            return response()->json([
                'success' => true,
                'data' => $aspects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data aspek',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive(string $id)
    {
        try {
            $question = ProfilingQuestion::findOrFail($id);
            $question->is_active = !$question->is_active;
            $question->save();

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diubah',
                'data' => $question
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
