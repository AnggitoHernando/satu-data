<?php

namespace App\Http\Controllers;

use App\Models\Seksi;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\MenuInformasi;
use App\ApiJoomla;
use App\Models\Permohonan;

class HomeController extends Controller
{
    use ApiJoomla;
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

    public function ppid(Request $request)
    {
        $beritaTerkini = $this->getLatestArticles($request, 4);
        return Inertia::render('Home/Ppid/PpidHome', [
            'beritaTerkini' => $beritaTerkini,
        ]);
    }

    public function permohonan_informasi()
    {
        return Inertia::render('Home/Ppid/PermohonanInformasi');
    }

    public function lacakPermohonan(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $permohonan = null;
        if ($search !== '') {
            $permohonan = Permohonan::with(['permohonanAsal', 'keberatan'])
                ->where('nomor_registrasi', $search)
                ->first();
        }

        return Inertia::render('Home/Ppid/LacakPermohonanInformasi', [
            'query'      => $search,
            'searched'   => $search !== '',
            'found'      => $permohonan !== null,
            'permohonan' => $permohonan ? [
                'nomor_registrasi'       => $permohonan->nomor_registrasi,
                'jenis'                  => $permohonan->jenis,
                'jenis_label'            => $permohonan->jenis === 'informasi' ? 'Permohonan Informasi' : 'Permohonan Keberatan',
                'status'                 => $permohonan->status,
                'status_label'           => $permohonan->status_label,
                'nama_lengkap'           => $permohonan->nama_lengkap,
                'tujuan_penggunaan'      => $permohonan->tujuan_penggunaan,
                'tanggapan'              => $permohonan->tanggapan,
                'tanggal_tanggapan'      => $permohonan->tanggal_tanggapan?->format('d M Y'),
                'dibuat_pada'            => $permohonan->created_at->format('d M Y'),
                'permohonan_asal_nomor'  => $permohonan->permohonanAsal?->nomor_registrasi,
                'jumlah_keberatan'       => $permohonan->keberatan->count(),
            ] : null,
        ]);
    }

    public function permohonanKeberatan()
    {
        return Inertia::render('Home/Ppid/PermohonanKeberatan');
    }

    public function informasi_berkala()
    {

        $informasiBerkala = MenuInformasi::where('slug', 'informasi-berkala')->firstOrFail();
        return Inertia::render('Home/Ppid/InformasiPpid', [
            'listInformasi' => $informasiBerkala->childrenRecursive()->get(),
            'judul_banner' => 'Informasi Berkala',
            'sub_judul_banner' => 'Setiap Badan Publik wajib menyediakan Informasi Publik setiap saat yang meliputi: daftar seluruh Informasi Publik yang berada di bawah penguasaannya, tidak termasuk informasi yang dikecualikan; hasil keputusan Badan Publik dan pertimbangannya; seluruh kebijakan yang ada berikut dokumen pendukungnya; rencana kerja proyek termasuk di dalamnya perkiraan pengeluaran tahunan Badan Publik; perjanjian Badan Publik dengan pihak ketiga; informasi dan kebijakan yang disampaikan Pejabat Publik dalam pertemuan yang terbuka untuk umum; prosedur kerja pegawai Badan Publik yang berkaitan dengan pelayanan masyarakat; dan/atau laporan mengenai pelayanan akses Informasi Publik sebagaimana diatur dalam Undang-Undang ini. UU No. 14 Tahun 2008, Pasal 11',
        ]);
    }
    public function informasiSertaMerta()
    {

        $informasiBerkala = MenuInformasi::where('slug', 'informasi-serta-merta')->firstOrFail();
        return Inertia::render('Home/Ppid/InformasiPpid', [
            'listInformasi' => $informasiBerkala->childrenRecursive()->get(),
            'judul_banner' => 'Informasi Serta Merta',
            'sub_judul_banner' => 'Setiap Badan Publik wajib menyediakan Informasi Publik setiap saat yang meliputi: daftar seluruh Informasi Publik yang berada di bawah penguasaannya, tidak termasuk informasi yang dikecualikan; hasil keputusan Badan Publik dan pertimbangannya; seluruh kebijakan yang ada berikut dokumen pendukungnya; rencana kerja proyek termasuk di dalamnya perkiraan pengeluaran tahunan Badan Publik; perjanjian Badan Publik dengan pihak ketiga; informasi dan kebijakan yang disampaikan Pejabat Publik dalam pertemuan yang terbuka untuk umum; prosedur kerja pegawai Badan Publik yang berkaitan dengan pelayanan masyarakat; dan/atau laporan mengenai pelayanan akses Informasi Publik sebagaimana diatur dalam Undang-Undang ini. UU No. 14 Tahun 2008, Pasal 11',
        ]);
    }

    public function informasiSetiapSaat()
    {

        $informasiBerkala = MenuInformasi::where('slug', 'informasi-setiap-saat')->firstOrFail();
        return Inertia::render('Home/Ppid/InformasiPpid', [
            'listInformasi' => $informasiBerkala->childrenRecursive()->get(),
            'judul_banner' => 'Informasi Setiap saat',
            'sub_judul_banner' => 'Setiap Badan Publik wajib menyediakan Informasi Publik setiap saat yang meliputi: daftar seluruh Informasi Publik yang berada di bawah penguasaannya, tidak termasuk informasi yang dikecualikan; hasil keputusan Badan Publik dan pertimbangannya; seluruh kebijakan yang ada berikut dokumen pendukungnya; rencana kerja proyek termasuk di dalamnya perkiraan pengeluaran tahunan Badan Publik; perjanjian Badan Publik dengan pihak ketiga; informasi dan kebijakan yang disampaikan Pejabat Publik dalam pertemuan yang terbuka untuk umum; prosedur kerja pegawai Badan Publik yang berkaitan dengan pelayanan masyarakat; dan/atau laporan mengenai pelayanan akses Informasi Publik sebagaimana diatur dalam Undang-Undang ini. UU No. 14 Tahun 2008, Pasal 11',
        ]);
    }

    public function informasiDikecualikan()
    {

        $informasiBerkala = MenuInformasi::where('slug', 'informasi-dikecualikan')->firstOrFail();
        return Inertia::render('Home/Ppid/InformasiPpid', [
            'listInformasi' => $informasiBerkala->childrenRecursive()->get(),
            'judul_banner' => 'Informasi Dikecualikan',
            'sub_judul_banner' => 'Setiap Badan Publik wajib menyediakan Informasi Publik setiap saat yang meliputi: daftar seluruh Informasi Publik yang berada di bawah penguasaannya, tidak termasuk informasi yang dikecualikan; hasil keputusan Badan Publik dan pertimbangannya; seluruh kebijakan yang ada berikut dokumen pendukungnya; rencana kerja proyek termasuk di dalamnya perkiraan pengeluaran tahunan Badan Publik; perjanjian Badan Publik dengan pihak ketiga; informasi dan kebijakan yang disampaikan Pejabat Publik dalam pertemuan yang terbuka untuk umum; prosedur kerja pegawai Badan Publik yang berkaitan dengan pelayanan masyarakat; dan/atau laporan mengenai pelayanan akses Informasi Publik sebagaimana diatur dalam Undang-Undang ini. UU No. 14 Tahun 2008, Pasal 11',
        ]);
    }

    public function tampilkanMenu($fullPath)
    {
        $slugs = explode('/', $fullPath);
        $awalSlug = array_shift($slugs);
        $lastSlug = end($slugs);

        $menu = MenuInformasi::where('slug', $lastSlug)->with(['parent' => function ($query) {
            $query->select('id', 'nama_menu')->with('childrenRecursive');
        }])->firstorFail();

        $menuUtama = MenuInformasi::where('slug', $awalSlug)->firstorFail();
        $listMenu = $menuUtama->childrenRecursive()->get();
        $informasi = null;
        $lampiran = [];
        $halamanStatis = null;

        if ($menu->jenis_tampilan === 'halaman_statis') {
            $halamanStatis = $menu->halamanStatis()->first();
        } else if ($menu->jenis_tampilan === 'halaman_statis_kosong') {
            abort(404);
        } else if ($menu->jenis_tampilan === 'daftar_informasi') {

            $informasi = $menu->informasi()
                ->where('status', 'dapat_diakses')
                ->with('lampiran')
                ->orderByDesc('tahun')
                ->get();

            $lampiran = $informasi
                ->groupBy('tahun')
                ->map(function ($itemsPerTahun, $tahun) {
                    return [
                        'year' => (int) $tahun,
                        'documents' => $itemsPerTahun
                            ->flatMap(function ($item) {
                                // 1 informasi bisa punya beberapa lampiran, jadi di-flatten
                                // supaya masing-masing file jadi 1 baris dokumen sendiri
                                return $item->lampiran->map(fn($file) => [
                                    'name' => $file->nama_file,
                                    'tag'  => $item->periode ?? $item->kategori_label,
                                    'url'  => $file->file_path,
                                    'type' => $file->tipe_file,
                                ]);
                            })
                            ->values(),
                    ];
                })
                ->sortByDesc('year')
                ->values();
        }


        return Inertia::render('Home/Ppid/DetailInformasi', [
            'lampiran' => $lampiran,
            'menu' => $menu,
            'listMenu' => $listMenu,
            'selectedMenu' => $lastSlug,
            'halamanStatis' => $halamanStatis,
        ]);
    }
}
