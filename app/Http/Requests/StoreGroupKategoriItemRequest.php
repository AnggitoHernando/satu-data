<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupKategoriItemRequest extends FormRequest
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
            'group_kategori_id' => 'required|exists:group_kategoris,id',
            'nama_item' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'group_kategori_id.required' => 'Group kategori wajib dipilih.',
            'group_kategori_id.exists' => 'Group kategori yang dipilih tidak valid.',
            'nama_item.required' => 'Nama item wajib diisi.',
            'nama_item.string' => 'Nama item harus berupa teks.',
            'nama_item.max' => 'Nama item tidak boleh lebih dari 255 karakter.',
        ];
    }
}
