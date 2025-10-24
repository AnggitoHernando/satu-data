<?php

namespace App\Http\Controllers;

use App\Models\JenisData;
use App\Models\Seksi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PortalDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seksi = Seksi::select('id', 'nama_seksi')->get();
        $query = JenisData::with('seksi:id,nama_seksi')
            ->where('status_data', 'publik')
            ->orderByDesc('created_at')
            ->paginate(10);
        return Inertia::render(
            'Home/PortalData',
            [
                'list_seksi' => $seksi,
                'list_data' => $query
            ]
        );
    }
    public function search(Request $request)
    {
        $slugMap = [
            'tata-usaha' => 'Sub Bagian Tata Usaha',
            'pendidikan-madrasah' => 'Seksi Pendidikan Madrasah',
            'bimas-islam' => 'Seksi Bimbingan Masyarakat Islam',
            'phu' => 'Penyelenggara Haji dan Umroh',
            'penzawa' => 'Penyelenggara Zakat dan Wakaf',
            'pais' => 'Pendidikan Agama Islam',
            'pd-pontren' => 'Pendidikan Diniyah dan Pondok Pesantren',
        ];
        $search = $request->input('q');
        $slug = $request->input('seksi');

        $slugSeksi = $slugMap[$slug] ?? null;
        $data = JenisData::with('seksi:id,nama_seksi')
            ->when($search, function ($query, $search) {
                $query->where('judul_data', 'like', "%{$search}%");
            })
            ->when($slugSeksi, function ($query, $slugSeksi) {
                $query->whereHas('seksi', function ($q) use ($slugSeksi) {
                    $q->where('nama_seksi', $slugSeksi);
                });
            })
            ->where('status_data', 'publik')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query());

        $seksi = Seksi::select('id', 'nama_seksi')->get();
        return Inertia::render(
            'Home/PortalData',
            [
                'list_seksi' => $seksi,
                'list_data' => $data
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
