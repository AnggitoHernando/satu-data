<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permohonan;
use App\Http\Requests\StorePermohonanInformasiRequest;
use App\Http\Requests\StorePermohonanKeberatanRequest;
use App\UploadsFile;
use Inertia\Inertia;

class PermohonanController extends Controller
{
    use UploadsFile;

    public function index(Request $request)
    {
        // dd($request->all());
        $permohonanList = Permohonan::query()
            ->filter($request->only(['search', 'jenis', 'status']))
            ->paginate(10)
            ->through(function ($item) {
                // Menyisipkan accessor status_label ke dalam setiap row item
                return $item->append('status_label', 'cara_mendapatkan_label');
            })
            ->withQueryString();


        $statusCounts = Permohonan::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return Inertia::render('Admin/Ppid/Permohonan', [
            'permohonanList' => $permohonanList,
            'statusCounts'   => $statusCounts, // Send rekap count ke Vue
            'filters'        => [
                'search' => $request->input('search', ''),
                'jenis'  => $request->input('jenis', ''),
                'status' => $request->input('status', 'semua'),
            ]
        ]);
    }

    public function show(Permohonan $permohonan)
    {
        return Inertia::render('Admin/Ppid/DetailPermohonan', [
            'permohonan' => $permohonan->append('status_label', 'cara_mendapatkan_label'),
        ]);
    }

    public function updateStatus(Request $request, Permohonan $permohonan)
    {
        $validated = $request->validate([
            'status'     => 'required|in:diajukan,diverifikasi,diproses,ditanggapi,selesai,ditolak',
            'tanggapan'  => 'required_if:status,ditolak|nullable|string',
        ], [
            'tanggapan.required_if' => 'Alasan penolakan wajib diisi karena akan dikirimkan ke pemohon.',
        ]);

        $data = ['status' => $validated['status']];
        if (array_key_exists('tanggapan', $validated) && $validated['tanggapan'] !== null) {
            $data['tanggapan'] = $validated['tanggapan'];
            $data['tanggal_tanggapan'] = now();
        }

        $permohonan->update($data);

        return redirect()
            ->route('admin.permohonan.index')
            ->with('success', 'Permohonan ' . $permohonan->nomor_registrasi . ' berhasil diupdate.');
    }

    public function storeInformasi(StorePermohonanInformasiRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('bukti_identitas')) {
            $folderTujuan = 'uploads/permohonan-informasi';
            $uploadedData = $this->uploadFile(
                $request->file('bukti_identitas'),
                $folderTujuan
            );

            $validatedData = array_merge($validated, $uploadedData);
            $validated['bukti_identitas'] = $validatedData['file_path'];
        }
        $validated['jenis'] = 'informasi';
        Permohonan::create($validated);

        return redirect()
            ->route('home.ppid.permohonan_informasi')
            ->with('success', 'Informasi berhasil ditambahkan.');
    }

    public function storeKeberatan(StorePermohonanKeberatanRequest $request)
    {
        $validated = $request->validated();

        $asal = Permohonan::where('nomor_registrasi', $validated['nomor_registrasi_asal'])
            ->where('jenis', 'informasi')
            ->first();

        if (! $asal) {
            return back()
                ->withErrors(['nomor_registrasi_asal' => 'Nomor registrasi tidak ditemukan atau bukan permohonan informasi.'])
                ->withInput();
        }
        $permohonan = Permohonan::create([
            'jenis'              => 'keberatan',
            'permohonan_asal_id' => $asal->id,
            'nama_lengkap'       => $validated['nama_lengkap'],
            'email'              => $validated['email'],
            'pekerjaan'          => $validated['pekerjaan'] ?? null,
            'no_telepon'         => $validated['no_telepon'],
            'alamat'             => $validated['alamat_lengkap'],
            'alasan_keberatan'   => $validated['alasan_keberatan'],
        ]);

        return redirect()
            ->route('home.ppid.permohonan_keberatan')
            ->with('success', 'Permohonan keberatan berhasil dikirim.');
    }
}
