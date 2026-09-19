<?php

namespace App\Http\Controllers;

use App\Models\Seksi;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\MenuInformasi;

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

    public function ppid()
    {
        return Inertia::render('Home/Ppid/PpidHome');
    }

    public function permohonan_informasi()
    {
        return Inertia::render('Home/Ppid/PermohonanInformasi');
    }

    public function lacak_permohonan_informasi()
    {
        return Inertia::render('Home/Ppid/LacakPermohonanInformasi');
    }

    public function lacak_permohonan_keberatan()
    {
        return Inertia::render('Home/Ppid/PermohonanKeberatan');
    }

    public function informasi_berkala()
    {

        $informasiBerkala = MenuInformasi::where('slug', 'informasi-berkala')->first();
        return Inertia::render('Home/Ppid/InformasiPpid', [
            'listInformasi' => $informasiBerkala->childrenRecursive()->get(),
            'judul_banner' => 'Informasi Berkala',
            'sub_judul_banner' => 'Setiap Badan Publik wajib menyediakan Informasi Publik setiap saat yang meliputi: daftar seluruh Informasi Publik yang berada di bawah penguasaannya, tidak termasuk informasi yang dikecualikan; hasil keputusan Badan Publik dan pertimbangannya; seluruh kebijakan yang ada berikut dokumen pendukungnya; rencana kerja proyek termasuk di dalamnya perkiraan pengeluaran tahunan Badan Publik; perjanjian Badan Publik dengan pihak ketiga; informasi dan kebijakan yang disampaikan Pejabat Publik dalam pertemuan yang terbuka untuk umum; prosedur kerja pegawai Badan Publik yang berkaitan dengan pelayanan masyarakat; dan/atau laporan mengenai pelayanan akses Informasi Publik sebagaimana diatur dalam Undang-Undang ini. UU No. 14 Tahun 2008, Pasal 11',
        ]);
    }
    public function informasiSertaMerta()
    {

        $informasiBerkala = MenuInformasi::where('slug', 'informasi-serta-merta')->first();
        return Inertia::render('Home/Ppid/InformasiPpid', [
            'listInformasi' => $informasiBerkala->childrenRecursive()->get(),
            'judul_banner' => 'Informasi Serta Merta',
            'sub_judul_banner' => 'Setiap Badan Publik wajib menyediakan Informasi Publik setiap saat yang meliputi: daftar seluruh Informasi Publik yang berada di bawah penguasaannya, tidak termasuk informasi yang dikecualikan; hasil keputusan Badan Publik dan pertimbangannya; seluruh kebijakan yang ada berikut dokumen pendukungnya; rencana kerja proyek termasuk di dalamnya perkiraan pengeluaran tahunan Badan Publik; perjanjian Badan Publik dengan pihak ketiga; informasi dan kebijakan yang disampaikan Pejabat Publik dalam pertemuan yang terbuka untuk umum; prosedur kerja pegawai Badan Publik yang berkaitan dengan pelayanan masyarakat; dan/atau laporan mengenai pelayanan akses Informasi Publik sebagaimana diatur dalam Undang-Undang ini. UU No. 14 Tahun 2008, Pasal 11',
        ]);
    }
    public function informasiSetiapSaat()
    {

        $informasiBerkala = MenuInformasi::where('slug', 'informasi-setiap-saat')->first();
        return Inertia::render('Home/Ppid/InformasiPpid', [
            'listInformasi' => $informasiBerkala->childrenRecursive()->get(),
            'judul_banner' => 'Informasi Setiap saat',
            'sub_judul_banner' => 'Setiap Badan Publik wajib menyediakan Informasi Publik setiap saat yang meliputi: daftar seluruh Informasi Publik yang berada di bawah penguasaannya, tidak termasuk informasi yang dikecualikan; hasil keputusan Badan Publik dan pertimbangannya; seluruh kebijakan yang ada berikut dokumen pendukungnya; rencana kerja proyek termasuk di dalamnya perkiraan pengeluaran tahunan Badan Publik; perjanjian Badan Publik dengan pihak ketiga; informasi dan kebijakan yang disampaikan Pejabat Publik dalam pertemuan yang terbuka untuk umum; prosedur kerja pegawai Badan Publik yang berkaitan dengan pelayanan masyarakat; dan/atau laporan mengenai pelayanan akses Informasi Publik sebagaimana diatur dalam Undang-Undang ini. UU No. 14 Tahun 2008, Pasal 11',
        ]);
    }
    public function informasiDikecualikan()
    {

        $informasiBerkala = MenuInformasi::where('slug', 'informasi-dikecualikan')->first();
        return Inertia::render('Home/Ppid/InformasiPpid', [
            'listInformasi' => $informasiBerkala->childrenRecursive()->get(),
            'judul_banner' => 'Informasi Dikecualikan',
            'sub_judul_banner' => 'Setiap Badan Publik wajib menyediakan Informasi Publik setiap saat yang meliputi: daftar seluruh Informasi Publik yang berada di bawah penguasaannya, tidak termasuk informasi yang dikecualikan; hasil keputusan Badan Publik dan pertimbangannya; seluruh kebijakan yang ada berikut dokumen pendukungnya; rencana kerja proyek termasuk di dalamnya perkiraan pengeluaran tahunan Badan Publik; perjanjian Badan Publik dengan pihak ketiga; informasi dan kebijakan yang disampaikan Pejabat Publik dalam pertemuan yang terbuka untuk umum; prosedur kerja pegawai Badan Publik yang berkaitan dengan pelayanan masyarakat; dan/atau laporan mengenai pelayanan akses Informasi Publik sebagaimana diatur dalam Undang-Undang ini. UU No. 14 Tahun 2008, Pasal 11',
        ]);
    }
}
