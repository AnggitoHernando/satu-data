<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;

class StoreHalamanStatisRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id'               => 'nullable',
            'menu_id' => [
                'required',
                'exists:menu_informasi,id',
                // Pengecekan unique diabaikan (ignore) jika $this->id ada isinya (proses update)
                Rule::unique('halaman_statis', 'menu_id')->ignore($this->id),
            ],
            'judul'            => 'required|string',
            'isi_konten'       => 'required|string',
            'gambar_utama'     => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'meta_deskripsi'   => 'nullable|string',
            'galeri_halaman_statis'   => 'nullable|array|max:20',
            'galeri_halaman_statis.*' => 'file|mimes:jpg,jpeg,png,webp,gif|max:5120',

            'galeri_lama'            => 'nullable|array',
            'galeri_lama.*.id'       => [
                'required',
                'integer',
                Rule::exists('galeri_halaman_statis', 'id')->where('halaman_statis_id', $this->id)
            ],
            'galeri_lama.*.alt_text' => 'nullable|string|max:200',

            'hapus_galeri'   => 'nullable|array',
            'hapus_galeri.*' => [
                'integer',
                Rule::exists('galeri_halaman_statis', 'id')->where('halaman_statis_id', $this->id)
            ],
        ];
    }
    public function messages()
    {
        return [
            'menu_id.required' => 'Menu wajib diisi.',
            'judul.required'   => 'Judul wajib diisi.',
            'judul.string'     => 'Judul harus berupa string.',
            'isi_konten.required' => 'Isi konten wajib diisi.',
            'isi_konten.string'   => 'Isi konten harus berupa string.',
            'gambar_utama.file'  => 'Gambar utama harus berupa file.',
            'gambar_utama.mimes' => 'Gambar utama harus berupa file dengan format: jpg, jpeg, png, webp, atau gif.',
            'gambar_utama.max'  => 'Gambar utama tidak boleh lebih dari 5MB.',
            'meta_deskripsi.string' => 'Meta deskripsi harus berupa string.',
            'galeri_halaman_statis.array' => 'Galeri halaman statis harus berupa array.',
            'galeri_halaman_statis.max' => 'Galeri halaman statis tidak boleh lebih dari 20 file.',
            'galeri_halaman_statis.*.file' => 'Setiap file galeri harus berupa file.',
            'galeri_halaman_statis.*.mimes' => 'Setiap file galeri harus berupa file dengan format: jpg, jpeg, png, webp, atau gif.',
            'galeri_halaman_statis.*.max' => 'Setiap file galeri tidak boleh lebih dari 5MB.',
        ];
    }

    public function dataHalaman(): array
    {
        return Arr::except($this->validated(), [
            'galeri_halaman_statis',
            'galeri_lama',
            'hapus_galeri',
        ]);
    }

    public function galeri(): array
    {
        return [
            'baru'  => $this->file('galeri_halaman_statis', []),
            'hapus' => $this->input('hapus_galeri', []),
            'alt'   => collect($this->input('galeri_lama', []))->pluck('alt_text', 'id')->all(),
        ];
    }
}
