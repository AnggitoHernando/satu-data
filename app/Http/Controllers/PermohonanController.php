<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permohonan;
use App\Http\Requests\StorePermohonanInformasiRequest;
use App\UploadsFile;


class PermohonanController extends Controller
{
    use UploadsFile;
    public function storeInformasi(StorePermohonanInformasiRequest $request)
    {
        return redirect()->back()->withErrors([
            'email' => 'Email ini sudah tidak aktif atau diblokir.',
        ]);
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
}
