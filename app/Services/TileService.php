<?php
namespace App\Services;

use App\Repositories\TileRepository;

class TileService
{
    protected $repo;

    public function __construct(TileRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAllTiles()
    {
        $tiles = $this->repo->getAll();

        return $tiles->map(function ($tile) {
            $content = json_decode($tile->linked_content, true);
            
            // Parsing Logic untuk support Data dari BoardTilesSeeder
            $contentType = $content['content_type'] ?? null;
            $category = $content['category'] ?? $tile->category ?? null;

            // 1. Handle Scenario Category from Seeder
            if (isset($content['scenario_category'])) {
                $category = $content['scenario_category'];
                // Jika content_type kosong, kita anggap ini kategori skenario
                if (!$contentType) $contentType = 'scenario_category';
            }

            // 2. Handle Card Type (Risk/Chance/Quiz) Random from Seeder
            if (isset($content['card_type'])) {
                if (!$contentType) $contentType = $content['card_type']; // risk, chance, quiz
            }

            // 3. Handle Property Types from Seeder
            if (isset($content['type']) && !isset($content['content_type'])) {
                $contentType = $content['type']; // investment, savings, insurance, etc.
            }

            $contentId = $content['content_id'] ?? null;

            $contentTitle = $this->repo->getContentTitle($contentType, $contentId);

            // Get landed stats
            $stats = $this->repo->getLandedStats($tile->tile_id);

            return [
                'tile_id' => $tile->tile_id,
                'name' => $tile->name,
                'position' => (int) $tile->position_index,
                'type' => $tile->type,
                'content_type' => $contentType,
                'content_id' => $contentId,
                'content_title' => $contentTitle,
                'category' => $category,
                'landed_count' => $stats
            ];
        });
    }

    public function getTileDetail($id)
    {
        $tile = $this->repo->findById($id);
        if (!$tile)
            return null;

        $content = json_decode($tile->linked_content, true);

        // Parsing Logic (Sama dengan getAllTiles)
        $contentType = $content['content_type'] ?? null;
        $category = $content['category'] ?? $tile->category ?? null;

        if (isset($content['scenario_category'])) {
            $category = $content['scenario_category'];
            if (!$contentType) $contentType = 'scenario_category';
        }
        if (isset($content['card_type'])) {
            if (!$contentType) $contentType = $content['card_type'];
        }
        if (isset($content['type']) && !isset($content['content_type'])) {
            $contentType = $content['type'];
        }

        $contentId = $content['content_id'] ?? null;
        $contentTitle = $this->repo->getContentTitle($contentType, $contentId);
        $stats = $this->repo->getLandedStats($id);

        return [
            'tile_id' => $tile->tile_id,
            'name' => $tile->name,
            'type' => $tile->type,
            'content_type' => $contentType,
            'content_id' => $contentId,
            'category' => $category,
            'content_title' => $contentTitle,
            'landed_count' => $stats,
            'linked_content_raw' => $tile->linked_content // Raw JSON for edit form
        ];
    }

    public function createTile($data)
    {
        // Check if position already exists
        if ($this->repo->positionExists($data['position'])) {
            return ['success' => false, 'message' => 'Posisi sudah digunakan oleh tile lain'];
        }

        $tileId = $this->repo->create($data);
        return ['success' => true, 'tile_id' => $tileId, 'message' => 'Tile berhasil dibuat'];
    }

    public function updateTile($id, $data)
    {
        // Check if tile exists
        $tile = $this->repo->findById($id);
        if (!$tile) {
            return ['success' => false, 'message' => 'Tile tidak ditemukan'];
        }

        // Check if position already used by other tile
        if ($this->repo->positionExists($data['position'], $id)) {
            return ['success' => false, 'message' => 'Posisi sudah digunakan oleh tile lain'];
        }

        $this->repo->update($id, $data);
        return ['success' => true, 'message' => 'Tile berhasil diperbarui'];
    }

    public function getAvailableContents()
    {
        return $this->repo->getAvailableContents();
    }

    public function deleteTile($id)
    {
        $tile = $this->repo->findById($id);
        if (!$tile) {
            return ['success' => false, 'message' => 'Tile tidak ditemukan'];
        }

        $this->repo->delete($id);
        return ['success' => true, 'message' => 'Tile berhasil dihapus'];
    }
}