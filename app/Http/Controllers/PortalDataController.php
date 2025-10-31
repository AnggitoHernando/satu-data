<?php

namespace App\Http\Controllers;

use App\Models\JenisData;
use App\Models\JenisDataRecords;
use App\Models\Seksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PortalDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $seksi = Seksi::select('id', 'nama_seksi')->get();
        $query = JenisData::with('seksi:id,nama_seksi')
            ->where('status_data', 'publik')
            ->orderByDesc('created_at')
            ->paginate(10);
        $baseUrl = url('/api/portal-data');
        $currentParams = $request->except('page');
        $query->withPath($baseUrl);
        $query->appends($currentParams);
        return Inertia::render(
            'Home/PortalData',
            [
                'list_seksi' => $seksi,
                'list_data' => $query,
                'filters' => [
                    'q' => "",
                    'seksi' => $slugSeksi ?? 'semua',
                ]
            ]
        );
    }

    public function apiIndex(Request $request)
    {
        $slugMap = [
            'tata-usaha' => 'Sub Bagian Tata Usaha',
            'pendidikan-madrasah' => 'Pendidikan Madrasah',
            'bimas-islam' => 'Bimbingan Masyarakat Islam',
            'phu' => 'Penyelenggara Haji dan Umroh',
            'penzawa' => 'Penyelenggara Zakat dan Wakaf',
            'pais' => 'Pendidikan Agama Islam',
            'pd-pontren' => 'Pendidikan Diniyah dan Pondok Pesantren',
        ];
        $search = $request->input('q');
        $slug = $request->input('seksi');

        $slugSeksi = $slugMap[$slug] ?? null;
        if ($search !== "") {
            $query = $this->query_search($search, $slugSeksi);
        } else {
            $query = $this->query_non_search();
        }

        $data = $query->paginate(10)->appends($request->query());
        return response()->json($data);
    }

    public function search(Request $request)
    {
        $slugMap = [
            'tata-usaha' => 'Sub Bagian Tata Usaha',
            'pendidikan-madrasah' => 'Pendidikan Madrasah',
            'bimas-islam' => 'Bimbingan Masyarakat Islam',
            'phu' => 'Penyelenggara Haji dan Umroh',
            'penzawa' => 'Penyelenggara Zakat dan Wakaf',
            'pais' => 'Pendidikan Agama Islam',
            'pd-pontren' => 'Pendidikan Diniyah dan Pondok Pesantren',
        ];
        $search = $request->input('q');
        $slug = $request->input('seksi');

        $slugSeksi = $slugMap[$slug] ?? null;

        $seksi = Seksi::select('id', 'nama_seksi')->get();
        $query = $this->query_search($search, $slugSeksi);
        $data = $query->paginate(10);
        $baseUrl = url('/api/portal-data');
        $currentParams = $request->except('page');
        $data->withPath($baseUrl);

        $data->appends($currentParams);

        return Inertia::render(
            'Home/PortalData',
            [
                'list_seksi' => $seksi,
                'list_data' => $data,
                'filters' => [
                    'q' => $request->q,
                    'seksi' => $slugSeksi ?? 'semua',
                ]
            ]
        );
    }

    public function query_non_search()
    {
        $query = JenisData::with('seksi:id,nama_seksi')
            ->where('status_data', 'publik')
            ->orderByDesc('created_at');
        return $query;
    }

    public function query_search($search, $slugSeksi)
    {
        $sub1 = DB::table('jenis_data as a')
            ->select([
                'a.id',
                DB::raw('NULL AS jumlah_data')
            ])
            ->where('a.judul_data', 'like', "%{$search}%")
            ->where('a.status_data', '=', 'publik');

        $sub2 = DB::table('jenis_data_records as a')
            ->select([
                'a.jenis_data_id AS id',
                DB::raw('COUNT(a.jenis_data_id) as jumlah_data')
            ])
            ->join('jenis_data as b', 'b.id', '=', 'a.jenis_data_id')
            ->where('b.status_data', '=', 'publik');
        ($search === "" || $search === null ? $sub2->where('a.data_json', 'like', "NULL") : $sub2->where('a.data_json', 'like', "%{$search}%"))
            ->groupBy('a.jenis_data_id');
        // dd($sub2->toSql(), $sub2->getBindings());
        $unionSub = $sub1->unionAll($sub2);

        // Query utama
        $query = DB::table('jenis_data as a')
            ->select([
                'a.id',
                DB::raw('MAX(a.tahun) as tahun'),
                DB::raw('MAX(a.judul_data) as judul_data'),
                DB::raw('MAX(a.deskripsi) as deskripsi'),
                DB::raw('MAX(a.sumber_data) as sumber_data'),
                DB::raw('MAX(a.extension_file) as extension_file'),
                DB::raw('MAX(c.nama_seksi) as nama_seksi'),
                DB::raw('MAX(b.jumlah_data) as jumlah_data'),
            ])
            ->joinSub($unionSub, 'b', function ($join) {
                $join->on('b.id', '=', 'a.id');
            })
            ->join('seksi as c', 'c.id', '=', 'a.seksi_id')
            ->when($slugSeksi, function ($q) use ($slugSeksi) {
                $q->where('c.nama_seksi', $slugSeksi);
            })
            ->groupBy('a.id')
            ->orderByDesc('a.created_at');
        Log::info('Generated SQL:', [$query->toSql()]);
        // dd($query->toSql(), $query->getBindings());
        // dd($slugSeksi);
        return $query;
    }

    public function apiDetail(Request $request)
    {
        $search = $request->input('search');
        $id = $request->input('id');

        $fields = DB::table('jenis_data_fields')
            ->where('jenis_data_id', $id)
            ->orderBy('urutan')
            ->pluck('nama_field');

        $perPage = $request->perPage ?? 10;
        $records = DB::table('jenis_data_records')
            ->where('jenis_data_id', $id)
            ->where('data_json', 'like', "%{$search}%")
            ->paginate($perPage)
            ->appends($request->query());
        $records->getCollection()->transform(function ($r) {
            $r->data_json = json_decode($r->data_json, true);
            return $r;
        });

        return response()->json([
            'fields' => $fields,
            'records' => $records,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
