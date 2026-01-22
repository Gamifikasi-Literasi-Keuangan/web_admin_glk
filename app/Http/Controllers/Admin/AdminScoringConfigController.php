<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminScoringConfigController extends Controller
{
    /**
     * Get all scoring configuration records
     */
    public function index()
    {
        $configs = DB::table('scoring_config')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $configs
        ]);
    }

    /**
     * Update scoring configuration values
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'configs' => 'required|array',
            'configs.*.config_key' => 'required|string',
            'configs.*.config_value' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['configs'] as $config) {
                // Validasi tambahan berdasarkan config_key
                $value = $config['config_value'];

                switch ($config['config_key']) {
                    case 'max_player_score':
                        if ($value <= 0 || $value > 1000) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Max player score harus antara 0-1000'
                            ], 422);
                        }
                        break;

                    case 'sensitivity_factor':
                        if ($value < 0 || $value > 1) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Sensitivity factor harus antara 0-1'
                            ], 422);
                        }
                        break;

                    case 'min_score_multiplier':
                        if ($value < 0 || $value > 1) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Min score multiplier harus antara 0-1'
                            ], 422);
                        }
                        break;

                    case 'max_score_multiplier':
                        if ($value < 1 || $value > 10) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Max score multiplier harus antara 1-10'
                            ], 422);
                        }
                        break;
                }

                // Update config value
                DB::table('scoring_config')
                    ->where('config_key', $config['config_key'])
                    ->update([
                        'config_value' => $value,
                        'updated_at' => now()
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Konfigurasi skor berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui konfigurasi: ' . $e->getMessage()
            ], 500);
        }
    }
}
