<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class TileRepository
{
    public function getAll()
    {
        return DB::table('BoardTiles')->orderBy('position_index')->get();
    }

    public function findById($id)
    {
        return DB::table('BoardTiles')->where('tile_id', $id)->first();
    }

    public function getLandedStats($tileId)
    {
        return DB::table('telemetry')
            ->where('tile_id', $tileId)
            ->where('action', 'landed')
            ->count();
    }

    // Helper untuk ambil judul konten
    public function getContentTitle($type, $id)
    {
        if (!$id) return null;
        if ($type === 'scenario') return DB::table('scenarios')->where('id', $id)->value('title');
        if ($type === 'quiz') return DB::table('quiz_cards')->where('id', $id)->value('question');
        return DB::table('cards')->where('id', $id)->value('title');
    }
}