<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        ];
    }
}
