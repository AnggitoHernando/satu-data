<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\HalamanStatis;
use App\Models\MenuInformasi;
use App\Http\Requests\StoreHalamanStatisRequest;
use App\UploadsFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class HalamanStatisController extends Controller
{
    use UploadsFile;
    public function index(Request $request)
    {
        $listMenuStatis = MenuInformasi::query()
            ->with(['halamanStatis' => function ($query) {
                $query->select('id', 'menu_id');
            }])
            ->where('is_active', 1)
            ->where('tipe', 'halaman_statis')
            ->whereDoesntHave('children')
            ->filter($request->only(['search']))
            ->orderBy('urutan', 'asc')
            ->paginate(10)
            ->withQueryString();
        return Inertia::render('Admin/Ppid/HalamanStatis', [
            'listMenuStatis' => $listMenuStatis,
        ]);
    }

    public function create(MenuInformasi $menu)
    {
        return Inertia::render('Admin/Ppid/FormTambahHalamanStatis', [
            'menu' => $menu,
        ]);
    }

    public function getMenuStatis(Request $request)
    {
        $search = $request->input('q');
        $menu =  MenuInformasi::query()
            ->where('is_active', 1)
            ->where('tipe', 'halaman_statis')
            ->whereDoesntHave('children')
            ->whereDoesntHave('halamanStatis')
            ->where('nama_menu', 'like', '%' . $search . '%')
            ->orderBy('urutan', 'asc')
            ->get();
        return response()->json($menu);
    }

    public function store(StoreHalamanStatisRequest $request)
    {
        $validatedData = $request->validated();
        if ($request->hasFile('gambar_utama')) {
            $folderTujuan = 'uploads/HalamanStatis/GambarUtama';
            $uploadedData = $this->uploadFile(
                $request->file('gambar_utama'),
                $folderTujuan
            );
            $validatedData['gambar_utama'] = $uploadedData['file_path'];
        }
        DB::transaction(function () use ($validatedData, $request) {
            $halaman = HalamanStatis::create($validatedData);
            $halaman->simpanGaleri($request->galeri());
        });

        return redirect()
            ->route('admin.ppid.halaman-statis')
            ->with('success', 'Halaman Statis berhasil ditambahkan.');
    }

    public function edit(MenuInformasi $menu)
    {
        $menu->load('halamanStatis.galeri');
        return Inertia::render('Admin/Ppid/FormTambahHalamanStatis', [
            'menu' => $menu,
        ]);
    }

    public function update(StoreHalamanStatisRequest $request, HalamanStatis $halamanStatis)
    {
        $data = Arr::except($request->dataHalaman(), ['id', 'gambar_utama']);
        $gambarLama = null;

        if ($request->hasFile('gambar_utama')) {
            $upload = $this->uploadFile(
                $request->file('gambar_utama'),
                'uploads/HalamanStatis/GambarUtama'
            );
            $data['gambar_utama'] = $upload['file_path'];
            $gambarLama = $halamanStatis->gambar_utama;
        }
        try {
            DB::transaction(function () use ($halamanStatis, $data, $request) {
                $halamanStatis->update($data);
                $halamanStatis->simpanGaleri($request->galeri());
            });
        } catch (\Throwable $e) {
            if (isset($data['gambar_utama'])) {
                $halamanStatis->hapusFile($data['gambar_utama']);
            }
            throw $e;
        }

        $halamanStatis->hapusFile($gambarLama);

        return redirect()
            ->route('admin.ppid.halaman-statis')
            ->with('success', 'Halaman Statis berhasil diupdate.');
    }

    public function destroy(HalamanStatis $halamanStatis)
    {
        $this->deleteOldFile($halamanStatis->gambar_utama);
        $halamanStatis->delete();
        return redirect()
            ->route('admin.ppid.halaman-statis')
            ->with('success', 'Halaman Statis berhasil dihapus.');
    }

    private function deleteOldFile(?string $filePath): void
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }
}
