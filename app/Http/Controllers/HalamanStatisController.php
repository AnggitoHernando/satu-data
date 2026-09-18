<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\HalamanStatis;
use App\Models\MenuInformasi;
use App\Http\Requests\StoreHalamanStatisRequest;
use App\UploadsFile;
use Illuminate\Support\Facades\Storage;

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
        HalamanStatis::create($validatedData);
        return redirect()
            ->route('admin.ppid.halaman-statis')
            ->with('success', 'Halaman Statis berhasil ditambahkan.');
    }

    public function edit(MenuInformasi $menu)
    {
        $menu->load('halamanStatis');
        return Inertia::render('Admin/Ppid/FormTambahHalamanStatis', [
            'menu' => $menu,
        ]);
    }

    public function update(StoreHalamanStatisRequest $request, HalamanStatis $halamanStatis)
    {
        $validatedData = $request->validated();
        if ($request->hasFile('gambar_utama')) {
            $this->deleteOldFile($halamanStatis->gambar_utama);
            $folderTujuan = 'uploads/HalamanStatis/GambarUtama';
            $uploadedData = $this->uploadFile(
                $request->file('gambar_utama'),
                $folderTujuan
            );
            $validatedData['gambar_utama'] = $uploadedData['file_path'];
        } else {
            unset($validatedData['gambar_utama']);
        }
        $halamanStatis->update($validatedData);
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
