<?php
namespace App\Repositories;

use App\Models\Scenario;
use Illuminate\Support\Facades\DB;

class ScenarioRepository
{
    /**
     * Mengambil daftar skenario dengan filter & pagination
     */
    public function getPaginated($limit, $filters = [])
    {
        $query = Scenario::query();

        // Filter Pencarian Judul
        if (!empty($filters['search'])) {
            $query->where('title', 'like', "%{$filters['search']}%");
        }

        // Filter Kategori
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        // Filter Kesulitan
        if (!empty($filters['difficulty'])) {
            $query->where('difficulty', $filters['difficulty']);
        }

        // Urutkan terbaru & Load relasi options (agar efisien / Eager Loading)
        return $query->with('options')
                     ->orderBy('created_at', 'desc')
                     ->paginate($limit);
    }

    /**
     * Mengambil detail satu skenario beserta opsinya
     */
    public function findById($id)
    {
        return Scenario::with('options')->find($id);
    }

    /**
     * Membuat skenario baru beserta opsinya (Transaction)
     */
    public function createWithOptions($data, $options)
    {
        return DB::transaction(function () use ($data, $options) {
            // Buat Skenario Utama
            $scenario = Scenario::create($data);
            
            // Masukkan ID scenario ke setiap opsi dan pastikan response tidak null
            foreach ($options as &$opt) {
                $opt['scenarioId'] = $scenario->id;
                $opt['response'] = $opt['response'] ?? '-';
            }

            // Simpan Opsi Sekaligus
            $scenario->options()->createMany($options);
            
            return $scenario;
        });
    }

    /**
     * Update skenario & opsi (Transaction)
     */
    public function updateWithOptions($id, $data, $options)
    {
        return DB::transaction(function () use ($id, $data, $options) {
            $scenario = Scenario::findOrFail($id);
            $scenario->update($data);

            // Hapus opsi lama, buat yang baru (cara paling aman untuk update list)
            $scenario->options()->delete();
            
            // Masukkan ID scenario ke setiap opsi baru dan pastikan response tidak null
            foreach ($options as &$opt) {
                $opt['scenarioId'] = $scenario->id;
                $opt['response'] = $opt['response'] ?? '-';
            }
            
            $scenario->options()->createMany($options);
            
            return $scenario;
        });
    }

    public function delete($id)
    {
        $scenario = Scenario::find($id);
        if (!$scenario) return false;
        
        // Hapus options terlebih dahulu (foreign key constraint)
        $scenario->options()->delete();
        
        return $scenario->delete();
    }
}