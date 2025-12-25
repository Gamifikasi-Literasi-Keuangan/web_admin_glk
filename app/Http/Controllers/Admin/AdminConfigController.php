<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminConfigController extends Controller
{
    public function show()
    {
        $config = DB::table('config')->orderBy('id', 'desc')->first();
        return response()->json($config ?? []);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'minPlayers' => 'required|integer|min:1|max:100',
            'maxPlayers' => 'required|integer|min:1|max:100',
            'max_turns' => 'required|integer|min:1|max:500',
        ]);

        // Validate that minPlayers <= maxPlayers
        if ($validated['minPlayers'] > $validated['maxPlayers']) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal pemain tidak boleh lebih dari maksimal pemain'
            ], 422);
        }

        $config = DB::table('config')->orderBy('id', 'desc')->first();
        
        if ($config) {
            DB::table('config')->where('id', $config->id)->update([
                'minPlayers' => $validated['minPlayers'],
                'maxPlayers' => $validated['maxPlayers'],
                'max_turns' => $validated['max_turns'],
                'version' => DB::raw('version + 1'),
                'updated_at' => now()
            ]);
        } else {
            DB::table('config')->insert([
                'minPlayers' => $validated['minPlayers'],
                'maxPlayers' => $validated['maxPlayers'],
                'max_turns' => $validated['max_turns'],
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Konfigurasi berhasil diupdate'
        ]);
    }
}