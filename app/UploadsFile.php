<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

trait UploadsFile
{
    public function uploadFile(
        UploadedFile $file,
        string $directory = 'uploads',
        string $disk = 'public',
        int $maxWidth = 1600
    ): array {
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::random(12) . '.' . $extension;
        $path = $directory . '/' . $filename;

        $imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (in_array($extension, $imageExtensions, true)) {
            // Resize dulu kalau ini gambar — scaleDown() tidak akan
            // memperbesar gambar yang sudah kecil, cuma mengecilkan
            // yang lebih besar dari $maxWidth.
            $image = Image::read($file)->scaleDown(width: $maxWidth);

            $encoded = match ($extension) {
                'jpg', 'jpeg' => $image->toJpeg(quality: 80),
                'png'         => $image->toPng(),
                'webp'        => $image->toWebp(quality: 80),
                'gif'         => $image->toGif(),
            };

            Storage::disk($disk)->put($path, (string) $encoded);
            $ukuranFile = strlen((string) $encoded);
        } else {
            $file->storeAs($directory, $filename, $disk);
            $ukuranFile = $file->getSize();
        }

        return [
            'file_path'          => $path,
            'nama_original_file' => $originalName,
            'tipe_file'          => $extension,
            'ukuran_file'        => $ukuranFile,
        ];
    }
}
