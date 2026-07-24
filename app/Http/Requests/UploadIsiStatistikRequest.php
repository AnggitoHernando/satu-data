<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadIsiStatistikRequest extends FormRequest
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
            'file'        => 'required|file|mimes:xlsx,xls|max:5120',
            'kategori_id' => 'required|exists:kategori_data,id',
        ];
    }
    public function messages(): array
    {
        return [
            'file.required' => 'File wajib diunggah.',
            'file.file' => 'File harus berupa file yang valid.',
            'file.mimes' => 'File harus berupa file Excel (xlsx atau xls).',
            'file.max' => 'Ukuran file tidak boleh lebih dari 5MB.',
            'kategori_id.required' => 'Pilih kategori data terlebih dahulu.',
            'kategori_id.exists' => 'Kategori data tidak ditemukan.',
        ];
    }
}
