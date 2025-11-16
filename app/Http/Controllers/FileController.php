<?php

namespace App\Http\Controllers;

use App\Models\JenisData;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function download($id)
    {
        $data = JenisData::findOrFail($id);

        $path = storage_path("app/public/" . $data->file_path);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($path, $data->nama_original_file);
    }

    public function downloadTemplate()
    {
        $path = storage_path('app/Template/Template_Upload_MANDAT.xls');

        return response()->download($path, 'Template_Upload_MANDAT.xls');
    }
}
