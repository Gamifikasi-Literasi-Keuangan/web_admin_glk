<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminConfigController extends Controller
{
    public function show()
    {
        $config = DB::table('config')->orderBy('id', 'desc')->first();
        return response()->json($config ?? []);
    }
}