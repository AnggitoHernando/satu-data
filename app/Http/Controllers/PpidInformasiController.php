<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PpidInformasi;
use App\Models\Seksi;

class PpidInformasiController extends Controller
{
    public function tambahInformasi()
    {
        $listInformasi = PpidInformasi::query()
            ->with(['seksi' => function ($query) {
                $query->select('id', 'nama_seksi');
            }])
            ->with(['jenisData' => function ($query) {
                $query->select('id', 'judul_data');
            }])
            ->paginate(10)
            ->withQueryString();
        $listSeksi = Seksi::select("id", "nama_seksi")->get();
        return Inertia::render('Admin/Ppid/TambahInformasi', [
            'listInformasi' => $listInformasi,
            'listSeksi' => $listSeksi,
        ]);
    }

    public function tambahDataInformasi()
    {
        $listSeksi = Seksi::select("id", "nama_seksi")->get();
        return Inertia::render('Admin/Ppid/FormTambahInformasi', [
            'listSeksi' => $listSeksi,
        ]);
    }
}
