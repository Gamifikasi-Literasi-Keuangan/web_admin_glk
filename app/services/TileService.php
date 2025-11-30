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
            $content = json_decode($tile->linked_content);
            $contentId = $content->id ?? $content ?? null;

            return [
                'tile_id' => $tile->tile_id,
                'position' => (int) $tile->position_index,
                'type' => $tile->type,
                'content_id' => $contentId
            ];
        });
    }

    public function getTileDetail($id)
    {
        $tile = $this->repo->findById($id);
        if (!$tile) return null;

        $content = json_decode($tile->linked_content);
        $contentId = $content->id ?? $content ?? null;
        $title = $this->repo->getContentTitle($tile->type, $contentId);
        $stats = $this->repo->getLandedStats($id);

        return [
            'tile_id' => $tile->tile_id,
            'type' => $tile->type,
            'linked_content' => [
                'id' => $contentId,
                'title' => $title
            ],
            'stats' => ['landed' => $stats]
        ];
    }
}