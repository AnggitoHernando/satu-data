<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
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
            'nama_menu' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'tipe'      => 'required|in:daftar_informasi,halaman_statis',
            'is_active' => 'required|boolean',
            'parent_id' => 'nullable|integer',
            'urutan' => 'required|integer',
        ];
    }
    public function messages(): array
    {
        return [
            'nama_menu.required' => 'Nama Menu wajib diisi',
            'nama_menu.max' => 'Nama Menu tidak boleh lebih dari 255 karakter',
            'slug.required' => 'Slug wajib diisi',
            'slug.max' => 'Slug tidak boleh lebih dari 255 karakter',
            'tipe.required' => 'Tipe Menu wajib diisi',
            'tipe.max' => 'Tipe Menu tidak boleh lebih dari 255 karakter',
            'is_active.required' => 'Is Active wajib diisi',
            'parent_id.required' => 'Parent ID wajib diisi',
            'urutan.required' => 'Urutan Menu wajib diisi',
            'urutan.integer' => 'Urutan Menu harus berupa integer',
        ];
    }
}
