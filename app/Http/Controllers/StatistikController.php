<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupKategoriItemRequest;
use App\Http\Requests\StoreGroupKategoriRequest;
use App\Http\Requests\StoreIsiStatistikRequest;
use App\Http\Requests\StoreKategoriDataRequest;
use App\Http\Requests\UpdateKategoriDataRequest;
use App\Http\Requests\UploadIsiStatistikRequest;
use App\Models\GroupKategori;
use App\Models\GroupKategoriItem;
use App\Models\Statistik;
use Illuminate\Http\Request;
use App\Models\KategoriData;
use App\Models\IsiStatistik;
use App\Models\JenisData;
use App\Models\Seksi;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StatistikMultiSheetExport;
use App\Imports\StatistikMultiSheetImport;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreBulkItemsRequest;

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

    public function storeAddGroupKecamatan(KategoriData $kategori)
    {

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

        $sudahAda = GroupKategori::where('kategori_data_id', $kategori->id)
            ->whereIn('nama_group', $kecamatan)
            ->pluck('nama_group')
            ->toArray();

        $belumAda = array_filter($kecamatan, fn($nama) => !in_array($nama, $sudahAda));

        if (empty($belumAda)) {
            return response()->json([
                'message' => 'Semua kecamatan sudah ada.',
                'success' => false
            ]);
        }

        $now   = now();
        $items = array_map(fn($nama) => [
            'kategori_data_id' => $kategori->id,
            'nama_group'        => $nama,
            'created_at'        => $now,
            'updated_at'        => $now,
        ], array_values($belumAda));

        GroupKategori::insert($items);

        return response()->json([
            'success' => true,
            'message' => count($items) . ' kecamatan berhasil ditambahkan.',
            'added'   => count($items),
            'skipped' => count($sudahAda),
        ]);
    }

    public function storeAutoKecamatan(GroupKategori $groupKategori)
    {

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
        $sudahAda = GroupKategoriItem::where('group_kategori_id', $groupKategori->id)
            ->whereIn('nama_item', $kecamatan)
            ->pluck('nama_item')
            ->toArray();

        $belumAda = array_filter($kecamatan, fn($nama) => !in_array($nama, $sudahAda));
        if (empty($belumAda)) {
            return response()->json([
                'message' => 'Semua kecamatan sudah ada.',
                'success' => false
            ]);
        }

        $now   = now();
        $items = array_map(fn($nama) => [
            'group_kategori_id' => $groupKategori->id,
            'nama_item'         => $nama,
            'created_at'        => $now,
            'updated_at'        => $now,
        ], $belumAda);

        GroupKategoriItem::insert($items);

        return response()->json([
            'success' => true,
            'message' => count($items) . ' kecamatan berhasil ditambahkan.',
            'added'   => count($items),
            'skipped' => count($sudahAda),
        ]);
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

    public function downloadTemplate(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_data,id',
            'group_ids'   => 'required|array|min:1',
            'group_ids.*' => 'exists:group_kategoris,id',
        ]);

        $groups = GroupKategori::whereIn('id', $request->group_ids)
            ->where('kategori_data_id', $request->kategori_id)
            ->with('groupKategoriItems')
            ->get();

        if ($groups->isEmpty()) {
            return response()->json(['message' => 'Group tidak ditemukan.'], 404);
        }

        $kategori = KategoriData::find($request->kategori_id);
        $namaFile = 'template-' . Str::slug($kategori->nama_kategori) . '.xlsx';

        return Excel::download(new StatistikMultiSheetExport($groups), $namaFile);
    }

    public function uploadIsiStatistik(UploadIsiStatistikRequest $request)
    {
        $validated = $request->validated();

        $import = new StatistikMultiSheetImport($request->kategori_id);

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'File tidak dapat dibaca. Pastikan file sesuai template.',
                'error'   => $e->getMessage(),
            ], 422);
        }

        $results   = $import->getResults();
        $totalIn   = array_sum(array_column($results, 'inserted'));
        $totalUp   = array_sum(array_column($results, 'updated'));
        $allErrors = array_merge(...array_column($results, 'errors'));

        if (!empty($allErrors)) {
            return response()->json([
                'message'  => 'Upload selesai dengan beberapa error.',
                'inserted' => $totalIn,
                'updated'  => $totalUp,
                'results'  => $results,
                'errors'   => $allErrors,
            ], 422);
        }

        return response()->json([
            'message'  => 'Upload berhasil.',
            'inserted' => $totalIn,
            'updated'  => $totalUp,
            'results'  => $results,
        ]);
    }

    public function storeBulkGroupKategoriItems(StoreBulkItemsRequest $request)
    {
        $now      = now();
        $inserted = 0;
        $skipped  = 0;

        DB::transaction(function () use ($request, $now, &$inserted, &$skipped) {
            foreach ($request->group_ids as $groupId) {
                // Ambil item yang sudah ada di group ini
                $existing = GroupKategoriItem::where('group_kategori_id', $groupId)
                    ->pluck('nama_item')
                    ->map(fn($n) => strtolower($n))
                    ->toArray();

                $toInsert = [];
                foreach ($request->items as $namaItem) {
                    // Skip jika sudah ada (case-insensitive)
                    if (in_array(strtolower($namaItem), $existing)) {
                        $skipped++;
                        continue;
                    }

                    $toInsert[] = [
                        'group_kategori_id' => $groupId,
                        'nama_item'         => $namaItem,
                        'created_at'        => $now,
                        'updated_at'        => $now,
                    ];
                }

                if (!empty($toInsert)) {
                    GroupKategoriItem::insert($toInsert);
                    $inserted += count($toInsert);
                }
            }
        });

        return response()->json([
            'message'  => "{$inserted} item berhasil ditambahkan." . ($skipped > 0 ? " {$skipped} item dilewati karena sudah ada." : ""),
            'inserted' => $inserted,
            'skipped'  => $skipped,
        ]);
    }
}
