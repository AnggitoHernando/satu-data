<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permohonan;
use App\Http\Requests\StorePermohonanInformasiRequest;
use App\Http\Requests\StorePermohonanKeberatanRequest;
use App\UploadsFile;


class PermohonanController extends Controller
{
    use UploadsFile;
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
            ->route('home.ppid.permohonan_keberatan', $permohonan->nomor_registrasi)
            ->with('success', 'Permohonan keberatan berhasil dikirim.');
    }
}
