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
            'category' => $data['category'] ?? null, // Store category for scenario tiles
            'linked_content' => json_encode([
                'content_type' => $data['content_type'] ?? null,
                'content_id' => $data['content_id'] ?? null,
                'category' => $data['category'] ?? null
            ]),
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
                'category' => $data['category'] ?? null, // Store category for scenario tiles
                'linked_content' => json_encode([
                    'content_type' => $data['content_type'] ?? null,
                    'content_id' => $data['content_id'] ?? null,
                    'category' => $data['category'] ?? null
                ]),
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
        // For scenarios: get distinct categories (not individual scenarios)
        // Tile will store category, and game will random pick scenario from that category
        $scenarioCategories = DB::table('scenarios')
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->map(fn($cat) => [
                'category' => $cat,
                'title' => $cat, // Display name same as category
                'type' => 'scenario_category'
            ]);

        $risks = DB::table('cards')
            ->where('type', 'risk')
            ->select('id', 'title')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'title' => $c->title,
                'type' => 'risk'
            ]);

        $chances = DB::table('cards')
            ->where('type', 'chance')
            ->select('id', 'title')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'title' => $c->title,
                'type' => 'chance'
            ]);

        $quizzes = DB::table('quiz_cards')
            ->select('id', 'question as title')
            ->get()
            ->map(fn($q) => [
                'id' => $q->id,
                'title' => $q->title,
                'type' => 'quiz'
            ]);

        return [
            'scenario_categories' => $scenarioCategories,
            'risks' => $risks,
            'chances' => $chances,
            'quizzes' => $quizzes
        ];
    }

    public function delete($id)
    {
        return DB::table('boardtiles')->where('tile_id', $id)->delete();
    }
}