<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait UploadsFile
{
    public function uploadFile(
        UploadedFile $file,
        string $directory = 'uploads',
        string $disk = 'public'
    ): array {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $filename = Str::random(12) . '.' . $extension;

        $path = $file->storeAs($directory, $filename, $disk);

        return [
            'file_path'          => $path,
            'nama_original_file' => $originalName,
            'extension_file'     => $extension,
        ];
    }
}
