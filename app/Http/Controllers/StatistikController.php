<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupKategoriItemRequest;
use App\Http\Requests\StoreGroupKategoriRequest;
use App\Http\Requests\StoreKategoriDataRequest;
use App\Http\Requests\UpdateKategoriDataRequest;
use App\Models\GroupKategori;
use App\Models\GroupKategoriItem;
use App\Models\Statistik;
use Illuminate\Http\Request;
use App\Models\KategoriData;
use App\Models\IsiStatistik;
use App\Models\JenisData;
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
        $listKategoriData = KategoriData::query()
            ->with(['seksi' => function ($q) {
                $q->select('id', 'nama_seksi');
            }])
            ->with(['jenisData' => function ($q) {
                $q->select('id', 'judul_data');
            }])
            ->filter($request->only(['search', 'from', 'to', 'sortBy', 'sortDir', 'seksi_id']))->paginate(10)
            ->withQueryString();
        $listSeksi = Seksi::select("id", "nama_seksi")->get();
        return inertia('Admin/Statistik/KategoriData', [
            "listKategoriData" => $listKategoriData,
            "listSeksi" => $listSeksi
        ]);
    }

    public function searchReferensi(Request $request)
    {
        $search = $request->input('q');
        $results = JenisData::query()
            ->where("status_data", "publik")  // ← filter utama diluar group
            ->where(function ($q) use ($search) {
                $q->where('judul_data', 'like', "%{$search}%")
                    ->orWhereHas('seksi', function ($q) use ($search) {
                        $q->where('nama_seksi', 'like', "%{$search}%");
                    });
            })
            ->select('id', 'judul_data')
            ->get();

        return response()->json($results);
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

    public function GroupKategori(KategoriData $kategori)
    {
        $groupKategori = GroupKategori::where("kategori_data_id", $kategori->id)->get();
        return response()->json($groupKategori);
    }

    public function storeGroupKategori(StoreGroupKategoriRequest $request, KategoriData $kategori)
    {
        $validated = $request->validated();

        $groupKategori = GroupKategori::create([
            'nama_group' => $validated['nama_group'],
            'kategori_data_id' => $kategori->id,
        ]);

        return response()->json($groupKategori);
    }

    public function updateGroupKategori(StoreGroupKategoriRequest $request, GroupKategori $groupKategori)
    {
        $validated = $request->validated();

        $groupKategori->update($validated);
        return response()->json($groupKategori);
    }

    public function destroyGroupKategori(GroupKategori $groupKategori)
    {
        $groupKategori->delete();
        return response()->json(['message' => 'Group kategori berhasil dihapus.']);
    }

    public function GroupKategoriItem(GroupKategori $groupKategori)
    {
        $items = $groupKategori->groupKategoriItems()->get();
        return response()->json($items);
    }
    public function storeGroupKategoriItem(StoreGroupKategoriItemRequest $request)
    {
        $validated = $request->validated();

        $groupKategoriItem = GroupKategoriItem::create($validated);
        return response()->json($groupKategoriItem);
    }

    public function destroyGroupKategoriItem(GroupKategoriItem $groupKategoriItem)
    {
        $groupKategoriItem->delete();
        return response()->json(['message' => 'Group kategori item berhasil dihapus.']);
    }

    function IsiStatistik()
    {
        $isiStatistik = IsiStatistik::query()
            ->with("kategoriData.seksi")
            ->filter(request()->only(['search', 'from', 'to', 'sortBy', 'sortDir', 'seksi_id']))->paginate(10)->withQueryString();
        $listSeksi = Seksi::select("id", "nama_seksi")->get();
        return inertia('Admin/Statistik/IsiStatistik', [
            "isiStatistik" => $isiStatistik,
            "listSeksi" => $listSeksi
        ]);
    }

    public function destroyIsiStatistik(IsiStatistik $isiStatistik)
    {
        $isiStatistik->delete();
        return redirect()->back()->with('success', 'Isi statistik berhasil dihapus.');
    }
}
