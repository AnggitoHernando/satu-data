<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupKategoriItemRequest;
use App\Http\Requests\StoreGroupKategoriRequest;
use App\Http\Requests\StoreIsiStatistikRequest;
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

    public function storeAutoKecamatan(GroupKategori $groupKategori)
    {
        if ($groupKategori->groupKategoriItems()->exists()) {
            return response()->json(['success' => false, 'message' => 'Items sudah ada']);
        }

        $kecamatan = [
            'Kebomas',
            'Gresik',
            'Manyar',
            'Duduksampeyan',
            'Bungah',
            'Sidayu',
            'Ujungpangkah',
            'Panceng',
            'Tambak',
            'Sangkapura',
            'Dukun',
            'Balongpanggang',
            'Benjeng',
            'Cerme',
            'Menganti',
            'Kedamean',
            'Wringinanom',
            'Driyorejo',
        ];

        $now   = now();
        $items = array_map(fn($nama) => [
            'group_kategori_id' => $groupKategori->id,
            'nama_item'         => $nama,
            'created_at'        => $now,
            'updated_at'        => $now,
        ], $kecamatan);

        GroupKategoriItem::insert($items);

        return response()->json(['success' => true, 'message' => 'Kecamatan berhasil ditambahkan.']);
    }

    # Isi Statistik
    function getKategoriData(Request $request)
    {
        $search = $request->input('q');
        $results = KategoriData::query()
            ->where(function ($q) use ($search) {
                $q->where('nama_kategori', 'like', "%{$search}%")
                    ->orWhereHas('seksi', function ($q) use ($search) {
                        $q->where('nama_seksi', 'like', "%{$search}%");
                    });
            })
            ->select('id', 'nama_kategori')
            ->get();

        return response()->json($results);
    }

    function getGroupKategori($kategoriDataId)
    {
        $results = GroupKategori::query()
            ->where('kategori_data_id', $kategoriDataId)
            ->select('id', 'nama_group')
            ->get();

        return response()->json($results);
    }

    function getGroupKategoriItem($groupKategoriId)
    {
        $results = GroupKategoriItem::query()
            ->where('group_kategori_id', $groupKategoriId)
            ->select('id', 'nama_item')
            ->get();

        return response()->json($results);
    }

    function getGroupKategoriItemBatch($groupKategoriId, $tahun)
    {
        $results = GroupKategoriItem::query()
            ->where('group_kategori_id', $groupKategoriId)
            ->with(['isiStatistik' => function ($q) use ($tahun) {
                $q->where('tahun', $tahun)
                    ->select('id', 'group_kategori_item_id', 'tahun', 'value');
            }])
            ->select('id', 'nama_item')
            ->get();

        return response()->json($results);
    }

    function IsiStatistik()
    {
        $isiStatistik = IsiStatistik::query()
            ->with(['groupKategoriItem' => function ($q) {
                $q->select('id', 'nama_item', 'group_kategori_id')
                    ->with(['groupKategori' => function ($q2) {
                        $q2->select('id', 'nama_group', 'kategori_data_id')
                            ->with(['kategoriData' => function ($q3) {
                                $q3->select('id', 'nama_kategori', 'seksi_id')
                                    ->with(['seksi' => function ($q4) {
                                        $q4->select('id', 'nama_seksi');
                                    }]);
                            }]);
                    }]);
            }])
            ->filter(request()->only(['search', 'from', 'to', 'sortBy', 'sortDir', 'seksi_id']))->paginate(10)->withQueryString();
        $listSeksi = Seksi::select("id", "nama_seksi")->get();
        return inertia('Admin/Statistik/IsiStatistik', [
            "isiStatistik" => $isiStatistik,
            "listSeksi" => $listSeksi
        ]);
    }

    public function storeIsiStatistik(StoreIsiStatistikRequest $request)
    {
        $validated = $request->validated();

        IsiStatistik::updateOrCreate(
            [
                'group_kategori_item_id' => $validated['group_kategori_item_id'],
                'tahun'                  => $validated['tahun'],
            ],
            [
                'value' => $validated['value'],
            ]
        );
        return redirect()->back()->with('success', 'Isi statistik berhasil ditambahkan.');
    }

    public function destroyIsiStatistik(IsiStatistik $isiStatistik)
    {
        $isiStatistik->delete();
        return redirect()->back()->with('success', 'Isi statistik berhasil dihapus.');
    }
}
