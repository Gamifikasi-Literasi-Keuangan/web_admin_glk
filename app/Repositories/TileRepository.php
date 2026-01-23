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
        // Use provided tile_id if available, otherwise generate random ID
        $tileId = $data['tile_id'] ?? ('t' . substr(md5(uniqid()), 0, 8));
        
        // Store linked_content directly from user input
        $linkedContent = $data['linked_content'] ?? null;
        
        // Priority: use category from data, then from linked_content
        $category = $data['category'] ?? $linkedContent['scenario_category'] ?? $linkedContent['category'] ?? null;
        
        DB::table('boardtiles')->insert([
            'tile_id' => $tileId,
            'name' => $data['name'],
            'type' => $data['type'],
            'position_index' => $data['position'],
            'category' => $category,
            'linked_content' => $linkedContent ? json_encode($linkedContent) : null,
        ]);
        return $tileId;
    }

    public function update($id, $data)
    {
        // Store linked_content directly from user input
        $linkedContent = $data['linked_content'] ?? null;
        
        return DB::table('boardtiles')
            ->where('tile_id', $id)
            ->update([
                'name' => $data['name'],
                'type' => $data['type'],
                'position_index' => $data['position'],
                'category' => $linkedContent['scenario_category'] ?? $linkedContent['category'] ?? null,
                'linked_content' => $linkedContent ? json_encode($linkedContent) : null,
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

    /**
     * Build linked_content JSON structure based on tile type
     * Matches BoardTilesSeeder format for game engine compatibility
     */
    private function buildLinkedContent($data)
    {
        $type = $data['type'] ?? null;
        $contentType = $data['content_type'] ?? null;
        $contentId = $data['content_id'] ?? null;
        $category = $data['category'] ?? null;

        // START/FINISH tiles don't need linked content
        if (in_array($type, ['start', 'finish'])) {
            return null;
        }

        // SCENARIO tiles - use scenario_category format
        if ($type === 'scenario') {
            return [
                'scenario_category' => $category ?? $contentType
            ];
        }

        // PROPERTY tiles - use type format with sub-type
        if ($type === 'property') {
            return [
                'type' => $contentType ?? 'investment',
                'risk_level' => 'medium' // Default risk level
            ];
        }

        // RISK tiles
        if ($type === 'risk') {
            if ($contentId) {
                return [
                    'card_type' => 'risk',
                    'card_id' => $contentId
                ];
            }
            return [
                'card_type' => 'risk',
                'random' => true
            ];
        }

        // CHANCE tiles
        if ($type === 'chance') {
            if ($contentId) {
                return [
                    'card_type' => 'chance',
                    'card_id' => $contentId
                ];
            }
            return [
                'card_type' => 'chance',
                'random' => true
            ];
        }

        // QUIZ tiles
        if ($type === 'quiz') {
            if ($contentId) {
                return [
                    'quiz_id' => $contentId
                ];
            }
            return [
                'quiz_category' => 'literasi_umum',
                'random' => true
            ];
        }

        // Fallback - generic format
        return [
            'content_type' => $contentType,
            'content_id' => $contentId,
            'category' => $category
        ];
    }
}