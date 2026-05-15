<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupKategoriRequest extends FormRequest
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
            'nama_group' => 'required|string|max:255',
            'kategori_data_id' => 'required|exists:kategori_data,id',
        ];
    }
    public function messages(): array
    {
        return [
            'nama_group.required' => 'Nama group kategori wajib diisi.',
            'nama_group.string' => 'Nama group kategori harus berupa teks.',
            'nama_group.max' => 'Nama group kategori tidak boleh lebih dari 255 karakter.',
            'kategori_data_id.required' => 'Kategori data wajib dipilih.',
            'kategori_data_id.exists' => 'Kategori data yang dipilih tidak valid.',
        ];
    }
}
