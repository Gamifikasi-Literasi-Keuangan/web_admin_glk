<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingDataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminTrainingDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = TrainingDataset::query();

            // Filter by cluster
            if ($request->has('cluster')) {
                $query->byCluster($request->cluster);
            }

            // Filter by active status
            if ($request->has('is_active')) {
                if ($request->is_active == '1' || $request->is_active === true) {
                    $query->active();
                } else {
                    $query->where('is_active', false);
                }
            }

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('cluster', 'like', "%{$search}%")
                      ->orWhere('notes', 'like', "%{$search}%");
                });
            }

            $perPage = $request->get('per_page', 15);
            $data = $query->latest()->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $data,
                'distribution' => TrainingDataset::getDistribution()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data training',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'pendapatan' => 'required|string|in:Tidak Ada,Sangat Rendah,Rendah,Sedang,Tinggi,Sangat Tinggi',
                'anggaran' => 'required|string|in:Tidak Ada,Sangat Rendah,Rendah,Sedang,Tinggi,Sangat Tinggi',
                'tabungan_dan_dana_darurat' => 'required|string|in:Tidak Ada,Sangat Rendah,Rendah,Sedang,Tinggi,Sangat Tinggi',
                'utang' => 'required|string|in:Tidak Ada,Sangat Rendah,Rendah,Sedang,Tinggi,Sangat Tinggi',
                'investasi' => 'required|string|in:Tidak Ada,Sangat Rendah,Rendah,Sedang,Tinggi,Sangat Tinggi',
                'asuransi_dan_proteksi' => 'required|string|in:Tidak Ada,Sangat Rendah,Rendah,Sedang,Tinggi,Sangat Tinggi',
                'tujuan_jangka_panjang' => 'required|string|in:Tidak Ada,Sangat Rendah,Rendah,Sedang,Tinggi,Sangat Tinggi',
                'cluster' => 'required|string|in:Financial Novice,Financial Explorer,Foundation Builder,Financial Architect,Financial Sage',
                'is_active' => 'nullable|boolean',
                'notes' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = TrainingDataset::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Data training berhasil ditambahkan',
                'data' => $data
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data training',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = TrainingDataset::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $data
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = TrainingDataset::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'pendapatan' => 'sometimes|string|in:Tidak Ada,Sangat Rendah,Rendah,Sedang,Tinggi,Sangat Tinggi',
                'anggaran' => 'sometimes|string|in:Tidak Ada,Sangat Rendah,Rendah,Sedang,Tinggi,Sangat Tinggi',
                'tabungan_dan_dana_darurat' => 'sometimes|string|in:Tidak Ada,Sangat Rendah,Rendah,Sedang,Tinggi,Sangat Tinggi',
                'utang' => 'sometimes|string|in:Tidak Ada,Sangat Rendah,Rendah,Sedang,Tinggi,Sangat Tinggi',
                'investasi' => 'sometimes|string|in:Tidak Ada,Sangat Rendah,Rendah,Sedang,Tinggi,Sangat Tinggi',
                'asuransi_dan_proteksi' => 'sometimes|string|in:Tidak Ada,Sangat Rendah,Rendah,Sedang,Tinggi,Sangat Tinggi',
                'tujuan_jangka_panjang' => 'sometimes|string|in:Tidak Ada,Sangat Rendah,Rendah,Sedang,Tinggi,Sangat Tinggi',
                'cluster' => 'sometimes|string|in:Financial Novice,Financial Explorer,Foundation Builder,Financial Architect,Financial Sage',
                'is_active' => 'nullable|boolean',
                'notes' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Data training berhasil diupdate',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data training',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = TrainingDataset::findOrFail($id);
            $data->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data training berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data training',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get options for select inputs
     */
    public function options()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'levels' => [
                    'Tidak Ada',
                    'Sangat Rendah',
                    'Rendah',
                    'Sedang',
                    'Tinggi',
                    'Sangat Tinggi'
                ],
                'clusters' => [
                    'Financial Novice',
                    'Financial Explorer',
                    'Foundation Builder',
                    'Financial Architect',
                    'Financial Sage'
                ]
            ]
        ]);
    }

    /**
     * Bulk delete training data
     */
    public function bulkDelete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'exists:training_datasets,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $count = TrainingDataset::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "{$count} data training berhasil dihapus"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data training',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
