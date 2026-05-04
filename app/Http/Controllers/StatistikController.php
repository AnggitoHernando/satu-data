<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKategoriDataRequest;
use App\Http\Requests\UpdateKategoriDataRequest;
use App\Models\Statistik;
use Illuminate\Http\Request;
use App\Models\KategoriData;
use App\Models\IsiStatistik;
use App\Models\Seksi;

class StatistikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function KategoriData(Request $request)
    {
        $listKategoriData = KategoriData::query()->with("seksi")
            ->filter($request->only(['search', 'from', 'to', 'sortBy', 'sortDir', 'seksi_id']))->paginate(10)
            ->withQueryString();
        $listSeksi = Seksi::select("id", "nama_seksi")->get();
        return inertia('Admin/Statistik/KategoriData', [
            "listKategoriData" => $listKategoriData,
            "listSeksi" => $listSeksi
        ]);
    }

    public function storeKategoriData(StoreKategoriDataRequest $request)
    {
        $validated = $request->validated();

        KategoriData::create($validated);
        return redirect()->back()->with('success', 'Kategori data berhasil ditambahkan.');
    }

    public function updateKategoriData(UpdateKategoriDataRequest $request, KategoriData $kategoriData)
    {
        $validated = $request->validated();

        $kategoriData->update($validated);
        return redirect()->back()->with('success', 'Kategori data berhasil diperbarui.');
    }

    public function destroyKategoriData(KategoriData $kategoriData)
    {
        $kategoriData->delete();
        return redirect()->back()->with('success', 'Kategori data berhasil dihapus.');
    }

    function IsiStatistik()
    {
        return inertia('Admin/Statistik/IsiStatistik');
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
    public function show(Statistik $statistik)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Statistik $statistik)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Statistik $statistik)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Statistik $statistik)
    {
        //
    }
}
