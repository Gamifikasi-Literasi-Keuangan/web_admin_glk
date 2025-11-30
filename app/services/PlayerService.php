<?php
namespace App\Services;

use App\Repositories\PlayerRepository;

class PlayerService
{
    protected $repo;

    public function __construct(PlayerRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getList($request)
    {
        // 1. Ambil data mentah dari Repository (Paginator Object)
        $paginator = $this->repo->getPaginated(
            $request->input('limit', 20),
            $request->input('search'),
            $request->input('sort'),
            $request->input('order', 'desc')
        );

        // 2. TRANSFORMASI DATA (PENTING!)
        // Kita format ulang setiap item agar strukturnya 'flat' dan mudah dibaca frontend
        $paginator->getCollection()->transform(function ($player) {
            return [
                // Mapping ID agar tombol Detail berfungsi
                'player_id' => $player->PlayerId, 
                
                // Data Pemain
                'name' => $player->name,
                'total_games' => $player->gamesPlayed,
                'joined_at' => $player->createdAt,

                // Data Relasi (Mengambil dari tabel lain)
                // Gunakan operator '??' (null coalescing) untuk mencegah error jika data kosong
                'username' => $player->user->username ?? '-', 
                'status' => ($player->user->is_active ?? 1) ? 'Active' : 'Banned',
                'cluster' => $player->profile->cluster ?? 'Belum Profiling',
            ];
        });

        return $paginator;
    }

    public function getDetail($id)
    {
        $player = $this->repo->findById($id);
        if (!$player) return null;

        return [
            'player_info' => [
                'id' => $player->PlayerId,
                'name' => $player->name,
                'username' => $player->user->username ?? '-',
                'status' => ($player->user->is_active ?? 1) ? 'Active' : 'Banned',
                'join_date' => $player->createdAt,
                'device_locale' => $player->locale
            ],
            // Pastikan relasi profile ada
            'ai_profile' => $player->profile ? [
                'cluster' => $player->profile->cluster,
                'traits' => $player->profile->traits, // Laravel otomatis decode JSON jika di-cast di model
                'weak_areas' => $player->profile->weak_areas,
                'initial_answers' => $player->profile->onboarding_answers ?? [],
                'ai_confidence' => ($player->profile->confidence_level * 100) . '%',
                'last_updated' => $player->profile->last_updated
            ] : null,
            'lifetime_stats' => [
               'total_games' => $player->gamesPlayed
               // Tambahkan stats lain jika perlu
            ]
        ];
    }

    public function getAnalysis($id)
    {
        $player = $this->repo->findById($id);
        if (!$player) return null;

        $stats = $this->repo->getAnalysisData($id);
        
        $weaknesses = $stats->filter(fn($s) => $s->accuracy < 60)->values();

        return [
            'player_id' => $id,
            'weaknesses' => $weaknesses,
            'recommendations' => $weaknesses->map(fn($w) => [
                'type' => 'Focus Area',
                'category' => $w->category,
                'title' => "Perbanyak latihan skenario {$w->category}",
                'reason' => "Akurasi Anda di topik ini di bawah 60%."
            ])
        ];
    }
}