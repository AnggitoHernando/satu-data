<?php

namespace App\Http\Controllers;

use App\Models\Seksi;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $seksi = Seksi::all();
        $subquery1 = DB::table('seksi')
            ->selectRaw('COUNT(id) AS jumlah_seksi, 0 AS jumlah_dokumen, 0 AS jumlah_dokumen_publik');

        $subquery2 = DB::table('jenis_data')
            ->selectRaw('0 AS jumlah_seksi, COUNT(id) AS jumlah_dokumen, 0 AS jumlah_dokumen_publik');

        $subquery3 = DB::table('jenis_data')
            ->selectRaw('0 AS jumlah_seksi, 0 AS jumlah_dokumen, COUNT(id) AS jumlah_dokumen_publik')
            ->where('status_data', 'publik');

        $query = DB::query()
            ->fromSub(
                $subquery1->unionAll($subquery2)->unionAll($subquery3),
                'a'
            )
            ->selectRaw('MAX(a.jumlah_seksi) AS jumlah_seksi, MAX(a.jumlah_dokumen) AS jumlah_dokumen, MAX(a.jumlah_dokumen_publik) AS jumlah_dokumen_publik');

        $statistik = $query->first();
        return Inertia::render('Home/Home', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'list_seksi' => $seksi,
            'statistik' => $statistik
        ]);
    }
}
