<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PpidInformasi;
use App\Models\Seksi;
use App\Models\JenisData;
use App\Http\Requests\StorePpidInformasiRequest;
use App\Http\Requests\UpdatePpidInformasiRequest;
use App\UploadsFile;
use Illuminate\Support\Facades\Storage;
use App\Models\MenuInformasi;

class PpidInformasiController extends Controller
{
    use UploadsFile;
    public function tambahInformasi(Request $request)
    {
        $listInformasi = PpidInformasi::query()
            ->with(['seksi' => function ($query) {
                $query->select('id', 'nama_seksi');
            }])
            ->with(['jenisData' => function ($query) {
                $query->select('id', 'judul_data', 'file_path');
            }])
            ->with(['lampiran' => function ($query) {
                $query->select('ppid_informasi_id', 'id', 'nama_file', 'file_path', 'tipe_file', 'ukuran_file');
            }])
            ->filter($request->only(['search', 'from', 'to', 'sortBy', 'sortDir', 'seksi_id']))
            ->paginate(10)
            ->withQueryString();
        $listSeksi = Seksi::select("id", "nama_seksi")->get();
        return Inertia::render('Admin/Ppid/TambahInformasi', [
            'listInformasi' => $listInformasi,
            'listSeksi' => $listSeksi,
        ]);
    }

    public function tambahDataInformasi()
    {
        $listSeksi = Seksi::select("id", "nama_seksi")->get();
        return Inertia::render('Admin/Ppid/FormTambahInformasi', [
            'listSeksi' => $listSeksi,

        ]);
    }

    public function getJenisData(Request $request)
    {
        $search = $request->input('q');
        $jenisData = JenisData::query()
            ->where('judul_data', 'like', '%' . $search . '%')
            ->select('id', 'judul_data')
            ->get();
        return response()->json($jenisData);
    }

    public function getMenu(Request $request)
    {
        $search = $request->input('q');
        $menu =  MenuInformasi::query()
            ->where('is_active', 1)
            ->where('tipe', 'daftar_informasi')
            ->whereDoesntHave('children')
            ->where('nama_menu', 'like', '%' . $search . '%')
            ->orderBy('urutan', 'asc')
            ->get();
        return response()->json($menu);
    }

    public function storeInformasi(StorePpidInformasiRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['status'] = 'dapat_diakses';
        $validatedData['waktu_pembuatan'] = now();
        $validatedData['waktu_penguasaan'] = now();

        $ppidInformasi = PpidInformasi::create(collect($validatedData)->except('file_path')->toArray());
        if ($request->hasFile('file_path')) {
            $folderTujuan = 'uploads/' . $request->kategori;
            $uploadedData = $this->uploadFile(
                $request->file('file_path'),
                $folderTujuan
            );

            $validatedData = array_merge($validatedData, $uploadedData);
            $ppidInformasi->lampiran()->create([
                'nama_file'   => $validatedData['nama_original_file'],
                'file_path'   => $validatedData['file_path'],
                'tipe_file'   => $validatedData['tipe_file'],
                'ukuran_file' => $validatedData['ukuran_file'],
                'urutan'      => 1,
            ]);
        }
        return redirect()
            ->route('admin.ppid.tambah-informasi')
            ->with('success', 'Informasi berhasil ditambahkan.');
    }

    public function updateInformasi(UpdatePpidInformasiRequest $request, PpidInformasi  $ppidInformasi)
    {
        $validatedData = $request->validated();
        if ($validatedData['jenis_data_id'] === null && $validatedData['file_path'] === null) {
            return redirect()->back()->withErrors([
                'jenis_data_id' => 'Jenis data wajib diisi.',
                'file_path' => 'File wajib diunggah jika tidak memiliki dari portal data.',
            ]);
        }
        $ppidInformasi->load('lampiran');

        if ($request->hasFile('file_path')) {
            $this->deleteOldFile($ppidInformasi->lampiran[0]->file_path);

            $folderTujuan = 'uploads/' . $request->kategori;
            $uploadedData = $this->uploadFile(
                $request->file('file_path'),
                $folderTujuan
            );
            $validatedData = array_merge($validatedData, $uploadedData);
            $validatedData['jenis_data_id'] = null;
            $ppidInformasi->lampiran()->update([
                'nama_file'   => $validatedData['nama_original_file'],
                'file_path'   => $validatedData['file_path'],
                'tipe_file'   => $validatedData['tipe_file'],
                'ukuran_file' => $validatedData['ukuran_file'],
                'urutan'      => 1,
            ]);
        } elseif (!empty($validatedData['jenis_data_id'])) {
            $this->deleteOldFile($ppidInformasi->file_path);

            $validatedData['file_path'] = null;
            $validatedData['nama_original_file'] = null;
            $validatedData['extension_file'] = null;
        } else {
            unset($validatedData['file_path']);
        }
        $ppidInformasi->update(collect($validatedData)->except('file_path')->toArray());
        return redirect()
            ->route('admin.ppid.tambah-informasi')
            ->with('success', 'Informasi berhasil diupdate.');
    }

    private function deleteOldFile(?string $filePath): void
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }


    public function destroyInformasi(PpidInformasi $ppidInformasi)
    {
        // dd($ppidInformasi->file_path);
        if ($ppidInformasi->file_path && Storage::disk('public')->exists($ppidInformasi->file_path)) {
            Storage::disk('public')->delete($ppidInformasi->file_path);
        }
        $ppidInformasi->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    public function editInformasi(PpidInformasi $ppidInformasi)
    {
        $listSeksi = Seksi::select("id", "nama_seksi")->get();
        $ppidInformasi->load('menu');
        $ppidInformasi->load('lampiran');
        $ppidInformasi->load('jenisData');
        return Inertia::render('Admin/Ppid/FormTambahInformasi', [
            'listSeksi' => $listSeksi,
            'ppidInformasi' => $ppidInformasi
        ]);
    }
}
