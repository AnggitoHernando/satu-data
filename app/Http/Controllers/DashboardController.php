<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;


class DashboardController extends Controller
{
    public function index()
    {
        $total_all = DB::table('jenis_data')->count();

        $results = DB::table('seksi as s')
            ->leftJoin(DB::raw('(
                    SELECT
                        seksi_id,
                        SUM(IF(status_data = "publik", 1, 0)) AS jumlah_data_publik,
                        SUM(IF(status_data = "private", 1, 0)) AS jumlah_data_private,
                        COUNT(id) AS jumlah_data_per_seksi
                    FROM jenis_data
                    GROUP BY seksi_id
                ) as j'), 'j.seksi_id', '=', 's.id')
            ->select(
                's.id',
                's.nama_seksi',
                DB::raw('IFNULL(j.jumlah_data_publik, 0) as jumlah_data_publik'),
                DB::raw('IFNULL(j.jumlah_data_private, 0) as jumlah_data_private'),
                DB::raw('IFNULL(j.jumlah_data_per_seksi, 0) as jumlah_data_per_seksi'),
                DB::raw($total_all . ' as jumlah_data_all'),
                DB::raw('(IFNULL(j.jumlah_data_per_seksi, 0) / ' . $total_all . ' * 100) as persentase_all'),
                DB::raw('IFNULL((j.jumlah_data_publik / j.jumlah_data_per_seksi) * 100, 0) as persentase_data_publik'),
                DB::raw('IFNULL((j.jumlah_data_private / j.jumlah_data_per_seksi) * 100, 0) as persentase_data_private')
            )
            ->get();

        $persentase_all = $results->map(fn($r) => [
            'nama_seksi' => $r->nama_seksi,
            'persentase' => round($r->persentase_all, 2)
        ])->toArray();

        $persentase_per_seksi = $results->map(fn($r) => [
            'nama_seksi' => $r->nama_seksi,
            'persentase_publik' => round($r->persentase_data_publik, 2),
            'persentase_private' => round($r->persentase_data_private, 2)
        ])->toArray();

        return Inertia::render(
            'Admin/Dashboard',
            [
                'jumlah_data_all' => $total_all,
                'persentase_all' => $persentase_all,
                'persentase_per_seksi' => $persentase_per_seksi
            ]
        );
    }
}
