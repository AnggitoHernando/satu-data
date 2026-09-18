<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePpidInformasiRequest extends FormRequest
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
        $allowedKategori = ['serta_merta', 'berkala', 'setiap_saat', 'dikecualikan'];
        $allowedBentukDokumen = ['soft_copy', 'hard_copy', 'keduanya'];
        return [
            'id' => 'nullable',
            'menu_id' => 'required|exists:menu_informasi,id',
            'nama_informasi' => 'required|string|max:255',
            'seksi_id' => 'required|exists:seksi,id',
            'unit_kerja' => 'required|string|max:255',
            'ringkasan' => 'nullable|string',
            'file_path' => ['nullable', 'required_without:jenis_data_id', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx', 'max:5120'],
            'jenis_data_id' => ['nullable', 'required_without:file_path', 'exists:jenis_data,id'],
            'tahun' => 'nullable|integer',
            'kategori' => 'required|string|' . Rule::in($allowedKategori),
            'bentuk_dokumen' => 'required|string|' . Rule::in($allowedBentukDokumen),
        ];
    }

    public function messages(): array
    {
        return [
            'nama_informasi.required' => 'Nama informasi wajib diisi.',
            'menu_id.required' => 'Menu wajib diisi.',
            'menu_id.exists' => 'Menu tidak ditemukan.',
            'seksi_id.required' => 'Pilih seksi terlebih dahulu.',
            'seksi_id.exists' => 'Seksi tidak ditemukan.',
            'unit_kerja.required' => 'Unit kerja wajib diisi.',
            'jenis_data_id.exists' => 'Jenis data tidak ditemukan.',
            'kategori.required' => 'Kategori wajib diisi.',
            'kategori.in' => 'Kategori tidak valid.',
            'bentuk_dokumen.required' => 'Bentuk dokumen wajib diisi.',
            'bentuk_dokumen.in' => 'Bentuk dokumen tidak valid.',
            'file_path.required_without' => 'File wajib diunggah jika tidak memilih dari portal data.',
            'file_path.file' => 'File harus berupa file yang valid.',
            'file_path.mimes' => 'File harus berupa file dengan format: pdf, doc, docx, xls, xlsx, ppt, pptx.',
            'file_path.max' => 'Ukuran file maksimal 5MB.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'jenis_data_id.required_without' => 'Jenis data wajib dipilih jika file tidak diunggah.',
        ];
    }
}
