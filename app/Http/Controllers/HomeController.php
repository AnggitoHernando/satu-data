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
use App\Models\PpidInformasi;
use Illuminate\Support\Facades\Storage;

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

    public function tampilkanMenu($fullPath)
    {
        $slugs = explode('/', $fullPath);
        $awalSlug = array_shift($slugs);
        $lastSlug = empty($slugs) ? $awalSlug : end($slugs);
        $menu = MenuInformasi::where('slug', $lastSlug)->with(['parent' => function ($query) {
            $query->select('id', 'nama_menu')->with('childrenRecursive');
        }])->firstorFail();

        $menuUtama = MenuInformasi::where('slug', $awalSlug)->firstorFail();
        $listMenu = $menuUtama->childrenRecursive()->get();
        $informasi = null;
        $lampiran = [];
        $halamanStatis = null;

        if ($menu->jenis_tampilan === 'grup') {
            $children = $menu->children()
                ->withCount('children')
                ->get()
                ->map(fn($child) => [
                    'id'        => $child->id,
                    'nama_menu' => $child->nama_menu,
                    'is_group'  => $child->children_count > 0,
                    'href'      => route('home.ppid.detailInformasi', [
                        'path' => $fullPath . '/' . $child->slug,
                    ]),
                ]);

            return Inertia::render('Home/Ppid/InformasiPpid', [
                'listInformasi' => $menu->childrenRecursive()->get(),
                'halamanStatis' => $menu->halamanStatis()->first(),
                'judul_banner' => $menu->nama_menu,
                'sub_judul_banner' => 'Setiap Badan Publik wajib menyediakan Informasi Publik setiap saat yang meliputi: daftar seluruh Informasi Publik yang berada di bawah penguasaannya, tidak termasuk informasi yang dikecualikan; hasil keputusan Badan Publik dan pertimbangannya; seluruh kebijakan yang ada berikut dokumen pendukungnya; rencana kerja proyek termasuk di dalamnya perkiraan pengeluaran tahunan Badan Publik; perjanjian Badan Publik dengan pihak ketiga; informasi dan kebijakan yang disampaikan Pejabat Publik dalam pertemuan yang terbuka untuk umum; prosedur kerja pegawai Badan Publik yang berkaitan dengan pelayanan masyarakat; dan/atau laporan mengenai pelayanan akses Informasi Publik sebagaimana diatur dalam Undang-Undang ini. UU No. 14 Tahun 2008, Pasal 11',
            ]);
        } else if ($menu->jenis_tampilan === 'halaman_statis') {
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
                                    'id'   => $item->id,
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

    public function detailDokumen($fullPath, $dokumenId)
    {

        $menu = MenuInformasi::where('slug', $fullPath)->firstOrFail();
        $dokumen = $menu->informasi()
            ->with('lampiran')
            ->whereKey($dokumenId)
            ->firstOrFail();
        $dokumenLainnya = PpidInformasi::query()
            ->with(['menu' => function ($query) {
                $query->select('id', 'nama_menu', 'slug');
            }])
            ->where('menu_id', '!=', $menu->id)
            ->inRandomOrder()
            ->limit(5)
            ->get([
                'id',
                'menu_id',
                'nama_informasi',
                'tahun',
                'kategori',
            ]);

        return Inertia::render('Home/Ppid/DetailDokumen', [
            'menu' => $menu,

            'dokumen' => [
                'id' => $dokumen->id,
                'judul' => $dokumen->nama_informasi,
                'tahun' => $dokumen->tahun,
                'ringkasan' => $dokumen->ringkasan,

                'lampiran' => $dokumen->lampiran->map(
                    fn($file) => [
                        'id' => $file->id,
                        'name' => $file->nama_file,
                        'url' => $file->file_path,
                        'type' => $file->tipe_file,
                    ]
                )->values(),
            ],
            'dokumenLainnya' => $dokumenLainnya,
        ]);
    }

    private const SORTS = ['relevan', 'terbaru', 'terlama'];

    public function searchPpidInformasi(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $sort = in_array($request->query('sort'), self::SORTS, true)
            ? $request->query('sort')
            : 'relevan';

        $query = PpidInformasi::query()
            ->with('lampiran')
            ->with('menu')
            // ->where('status', 'publish') // sesuaikan dengan nilai status "tampil" di aplikasi Anda
            ->when($request->filled('kategori'), fn($qb) => $qb->where('kategori', $request->query('kategori')))
            ->when($request->filled('tahun'), fn($qb) => $qb->where('tahun', $request->query('tahun')));

        // Setiap kata harus cocok di salah satu kolom (atau nama file lampiran).
        foreach (preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY) as $term) {
            $like = '%' . addcslashes($term, '%_\\') . '%';

            $query->where(function ($w) use ($like) {
                $w->where('nama_informasi', 'like', $like)
                    ->orWhere('ringkasan', 'like', $like)
                    ->orWhere('unit_kerja', 'like', $like)
                    ->orWhere('keterangan', 'like', $like)
                    ->orWhereHas('lampiran', fn($l) => $l->where('nama_file', 'like', $like));
            });
        }

        match ($sort) {
            'terbaru' => $query->orderByDesc('tahun')->orderByDesc('id'),
            'terlama' => $query->orderBy('tahun')->orderBy('id'),
            default => $q !== ''
                // Judul yang memuat kata kunci lengkap tampil lebih dulu.
                ? $query->orderByRaw('CASE WHEN nama_informasi LIKE ? THEN 0 ELSE 1 END', ['%' . addcslashes($q, '%_\\') . '%'])
                ->orderByDesc('tahun')
                : $query->orderByDesc('tahun')->orderByDesc('id'),
        };

        $informasi = $query->paginate(10)->withQueryString()->through(fn(PpidInformasi $i) => [
            'id' => $i->id,
            'menu' => $i->menu ? [
                'id' => $i->menu->id,
                'nama_menu' => $i->menu->nama_menu,
                'slug' => $i->menu->slug,
            ] : null,
            'nama_informasi' => $i->nama_informasi,
            'ringkasan' => $i->ringkasan,
            'kategori' => $i->kategori,
            'tahun' => $i->tahun,
            'bentuk_dokumen' => $i->bentuk_dokumen,
            'unit_kerja' => $i->unit_kerja,
            'lampiran' => $i->lampiran->map(fn($f) => [
                'id' => $f->id,
                'nama_file' => $f->nama_file,
                'tipe_file' => $f->tipe_file,
                'ukuran_file' => $f->ukuran_file, // diasumsikan dalam byte
                'url' => Storage::url($f->file_path), // ganti ke route download jika file privat
            ])->values(),
        ]);

        return Inertia::render('Home/Ppid/SearchInformasi', [
            'informasi' => $informasi,
            'filters' => ['q' => $q, 'sort' => $sort],
        ]);
    }
}
