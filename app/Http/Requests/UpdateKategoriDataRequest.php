<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKategoriDataRequest extends FormRequest
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
        $kategoriId = $this->route('kategoriData');
        $id = is_object($kategoriId) ? $kategoriId->id : $kategoriId;
        return [
            'nama_kategori' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('kategori_data', 'nama_kategori')->ignore($id),
            ],
            'seksi_id' => 'required|exists:seksi,id',
            'jenis_data_id' => 'nullable|exists:jenis_data,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah digunakan.',
            'seksi_id.required' => 'Pilih seksi terlebih dahulu.',
            'seksi_id.exists' => 'Seksi tidak ditemukan.',
            'jenis_data_id.exists' => 'Jenis data tidak ditemukan.',
        ];
    }
}
