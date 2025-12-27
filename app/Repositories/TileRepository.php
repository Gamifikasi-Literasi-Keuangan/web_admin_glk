<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class TileRepository
{
    public function getAll()
    {
        return DB::table('boardtiles')->orderBy('position_index')->get();
    }

    public function findById($id)
    {
        return DB::table('boardtiles')->where('tile_id', $id)->first();
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
        if (!$id)
            return null;
        if ($type === 'scenario')
            return DB::table('scenarios')->where('id', $id)->value('title');
        if ($type === 'quiz')
            return DB::table('quiz_cards')->where('id', $id)->value('question');
        return DB::table('cards')->where('id', $id)->value('title');
    }

    public function create($data)
    {
        // Generate short tile_id (max 10 chars to fit DB column)
        $tileId = 't' . substr(md5(uniqid()), 0, 8);
        DB::table('boardtiles')->insert([
            'tile_id' => $tileId,
            'name' => $data['name'],
            'type' => $data['type'],
            'position_index' => $data['position'],
            'linked_content' => json_encode([
                'content_type' => $data['content_type'] ?? null,
                'content_id' => $data['content_id'] ?? null
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return $tileId;
    }

    public function update($id, $data)
    {
        return DB::table('boardtiles')
            ->where('tile_id', $id)
            ->update([
                'name' => $data['name'],
                'type' => $data['type'],
                'position_index' => $data['position'],
                'linked_content' => json_encode([
                    'content_type' => $data['content_type'] ?? null,
                    'content_id' => $data['content_id'] ?? null
                ]),
                'updated_at' => now()
            ]);
    }

    public function positionExists($position, $excludeId = null)
    {
        $query = DB::table('boardtiles')->where('position_index', $position);
        if ($excludeId) {
            $query->where('tile_id', '!=', $excludeId);
        }
        return $query->exists();
    }

    public function getAvailableContents()
    {
        // Filter out soft-deleted records (deleted_at IS NULL)
        $scenarios = DB::table('scenarios')
            ->whereNull('deleted_at')
            ->select('id', 'title')
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'title' => $s->title,
                'type' => 'scenario'
            ]);

        $risks = DB::table('cards')
            ->where('type', 'risk')
            ->whereNull('deleted_at')
            ->select('id', 'title')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'title' => $c->title,
                'type' => 'risk'
            ]);

        $chances = DB::table('cards')
            ->where('type', 'chance')
            ->whereNull('deleted_at')
            ->select('id', 'title')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'title' => $c->title,
                'type' => 'chance'
            ]);

        $quizzes = DB::table('quiz_cards')
            ->whereNull('deleted_at')
            ->select('id', 'question as title')
            ->get()
            ->map(fn($q) => [
                'id' => $q->id,
                'title' => $q->title,
                'type' => 'quiz'
            ]);

        return [
            'scenarios' => $scenarios,
            'risks' => $risks,
            'chances' => $chances,
            'quizzes' => $quizzes
        ];
    }
}