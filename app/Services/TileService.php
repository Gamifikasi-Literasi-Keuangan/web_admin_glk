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
            $contentType = $content['content_type'] ?? null;
            $contentId = $content['content_id'] ?? null;
            
            // Get landed stats
            $stats = $this->repo->getLandedStats($tile->tile_id);

            return [
                'tile_id' => $tile->tile_id,
                'name' => $tile->name,
                'position' => (int) $tile->position_index,
                'type' => $tile->type,
                'content_type' => $contentType,
                'content_id' => $contentId,
                'landed_count' => $stats
            ];
        });
    }

    public function getTileDetail($id)
    {
        $tile = $this->repo->findById($id);
        if (!$tile) return null;

        $content = json_decode($tile->linked_content, true);
        $contentType = $content['content_type'] ?? null;
        $contentId = $content['content_id'] ?? null;
        $contentTitle = $this->repo->getContentTitle($contentType, $contentId);
        $stats = $this->repo->getLandedStats($id);

        return [
            'tile_id' => $tile->tile_id,
            'name' => $tile->name,
            'type' => $tile->type,
            'content_type' => $contentType,
            'content_id' => $contentId,
            'content_title' => $contentTitle,
            'landed_count' => $stats
        ];
    }
}