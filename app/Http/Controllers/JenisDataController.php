<?php

namespace App\Http\Controllers;

use App\Models\JenisData;
use App\Models\Seksi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class JenisDataController extends Controller
{
    public function index()
    {
        $seksi = Seksi::all();
        return Inertia::render(
            'admin/JenisData',
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
            'file_path' => 'nullable|file|max:2048',
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
            $validated['file_path'] = $request->file('file_path')->store('uploads/jenis_data', 'public');
        }

        $data = JenisData::create($validated);

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
        $jenisData->update([
            'status_data' => $request->status_data,
        ]);

        return response()->json(['success' => true]);
    }
}
