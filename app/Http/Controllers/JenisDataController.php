<?php

namespace App\Http\Controllers;

use App\Jobs\ImportJenisDataJob;
use App\Models\JenisData;
use App\Models\JenisDataFields;
use App\Models\JenisDataRecords;
use App\Models\Seksi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class JenisDataController extends Controller
{
    public function index()
    {
        $seksi = Seksi::all();
        return Inertia::render(
            'Admin/JenisData',
            [
                'list_seksi' => $seksi

            ]
        );
    }

    public function apiIndex(Request $request)
    {
        $query = JenisData::query()
            ->join('seksi', 'jenis_data.seksi_id', '=', 'seksi.id')
            ->select(
                'jenis_data.id as id',
                'jenis_data.seksi_id',
                'jenis_data.judul_data',
                'jenis_data.slug',
                'jenis_data.deskripsi',
                'jenis_data.tahun',
                'jenis_data.sumber_data',
                'jenis_data.status_data',
                'jenis_data.file_path',
                'jenis_data.created_at',
                'jenis_data.updated_at',
                'seksi.nama_seksi as nama_seksi'
            );

        if ($request->search) {
            $query->where('judul_data', 'like', '%' . $request->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }

        // Sorting
        if ($request->sortBy && $request->sortDir) {
            $query->orderBy($request->sortBy, $request->sortDir);
        } else {
            $query->orderBy('jenis_data.id', 'desc');
        }

        // Pagination
        $perPage = $request->perPage ?? 10;
        $jenisData = $query->paginate($perPage);

        return response()->json($jenisData);
    }
    public function store(Request $request)
    {
        // dd($request->all());
        // return;
        // return response()->json([
        //     'status' => 'error',
        //     'message' => 'Ada kesalahan input data',
        //     'errors' => [
        //         'judul_data' => ['Nama data wajib diisi'],
        //         'tahun' => ['Tahun wajib diisi'],
        //     ]
        // ], 422);
        $validated = $request->validate([
            'judul_data' => 'required|string|max:255',
            'seksi_id' => 'required|string',
            'slug' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'tahun' => 'required|string',
            'sumber_data' => 'required|string',
            'file_path' => 'required|file|max:2048',
        ]);

        $exists = JenisData::where('judul_data', $validated['judul_data'])
            ->where('tahun', $validated['tahun'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'judul_data' => 'Data dengan judul dan tahun ini sudah ada.',
            ]);
        }

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');

            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $filename = Str::random(12) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/jenis_data', $filename, 'public');

            $validated['file_path'] = $path;
            $validated['nama_original_file'] = $originalName;
            $validated['extension_file'] = $extension;
        }

        $slug = Str::slug($validated['judul_data']);
        $count = JenisData::where('slug', 'LIKE', "{$slug}%")->count();

        $validated['slug'] = $count ? "{$slug}-{$count}" : $slug;

        $data = JenisData::create($validated);
        // if (in_array($validated['extension_file'], ['xls', 'xlsx', 'csv'])) {
        //     try {
        //         $excelArray = Excel::toArray([], storage_path('app/public/' . $validated['file_path']))[0] ?? [];

        //         if (!empty($excelArray)) {
        //             // Baris pertama dianggap header (nama kolom)
        //             $headers = $excelArray[0];

        //             // Simpan ke tabel jenis_data_fields
        //             foreach ($headers as $index => $fieldName) {
        //                 JenisDataFields::create([
        //                     'jenis_data_id' => $data->id,
        //                     'nama_field' => $fieldName,
        //                     'jenis_data' => 'varchar',
        //                     'keterangan' => '',
        //                     'urutan' => $index + 1,
        //                 ]);
        //             }

        //             // Sisanya adalah data baris
        //             $rows = array_slice($excelArray, 1);

        //             foreach ($rows as $row) {
        //                 JenisDataRecords::create([
        //                     'jenis_data_id' => $data->id,
        //                     'data' => json_encode($row),
        //                 ]);
        //             }
        //         }
        //     } catch (\Exception $e) {
        //         // Kalau gagal parsing Excel, hapus data utama agar konsisten
        //         $data->delete();
        //         return response()->json([
        //             'status' => 'error',
        //             'message' => 'Ada kesalahan input data',
        //             'errors' => [
        //                 'file_path' => 'Gagal membaca file Excel: ' . $e->getMessage()
        //             ]
        //         ], 422);
        //     }
        // }
        ImportJenisDataJob::dispatch($data->id);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Data berhasil disimpan!'
        ]);
    }

    public function destroy(JenisData $jenisData)
    {
        if ($jenisData->file_path && Storage::disk('public')->exists($jenisData->file_path)) {
            Storage::disk('public')->delete($jenisData->file_path);
        }

        $jenisData->delete();

        return redirect()->back()->with('success', 'Data dan file berhasil dihapus.');
    }

    public function update(Request $request, JenisData $jenisData)
    {
        $validated = $request->validate([
            'judul_data' => 'required|string|max:255',
            'seksi_id' => 'required|string',
            'slug' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'tahun' => 'required|string',
            'sumber_data' => 'required|string',
            'file_path' => 'nullable|file|max:2048',
        ]);

        if ($request->hasFile('file_path')) {
            if ($jenisData->file_path && Storage::disk('public')->exists($jenisData->file_path)) {
                Storage::disk('public')->delete($jenisData->file_path);
            }

            $file = $request->file('file_path');

            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $filename = Str::random(12) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/jenis_data', $filename, 'public');

            $validated['file_path'] = $path;
            $validated['nama_original_file'] = $originalName;
            $validated['extension_file'] = $extension;
        } else {
            unset($validated['file_path']);
        }

        $slug = Str::slug($validated['judul_data']);
        $count = JenisData::where('slug', 'LIKE', "{$slug}%")->count();

        $validated['slug'] = $count ? "{$slug}-{$count}" : $slug;

        $data = $jenisData->update($validated);
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Data berhasil Diupdate!'
        ]);
    }
    public function updateStatus(Request $request, JenisData $jenisData)
    {
        $jenisData->update([
            'status_data' => $request->status_data,
        ]);

        return response()->json(['success' => true]);
    }
}
