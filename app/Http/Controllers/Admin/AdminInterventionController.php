<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminInterventionController extends Controller
{
    public function index()
    {
        // 1. Ambil data mentah dari database
        $templates = DB::table('InterventionTemplates')
            ->orderBy('level', 'asc')
            ->get();

        // 2. FORMAT ULANG DATA (Mapping)
        // Agar sesuai dengan yang diminta Frontend (settings.js)
        $formatted = $templates->map(function ($tpl) {
            return [
                // Mapping kolom DB ke nama variable Frontend
                'level_id' => $tpl->level, 
                'risk_label' => $tpl->risk_level, 
                'title' => $tpl->title_template,       // Ubah title_template -> title
                'message' => $tpl->message_template,   // Ubah message_template -> message
                
                // PENTING: Decode JSON string dari database menjadi Array PHP asli
                'actions' => json_decode($tpl->actions_template), 
                
                'is_mandatory' => (bool) $tpl->is_mandatory,
                
                // Tambahan logika warna untuk UI
                'ui_color' => match($tpl->risk_level) {
                    'Critical' => 'red',
                    'High' => 'orange',
                    'Medium' => 'yellow',
                    default => 'blue'
                }
            ];
        });

        // 3. Kirim data yang sudah rapi
        return response()->json(['data' => $formatted]);
    }
}