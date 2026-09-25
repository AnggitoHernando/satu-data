<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermohonanInformasiRequest extends FormRequest
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
            'nama_lengkap'      => 'required|string|max:255',
            'email'             => 'required|email:rfc,dns',
            'pekerjaan'         => 'nullable|string|max:255',
            'no_telepon'        => 'required|string|max:30',
            'alamat'            => 'required|string',
            'bukti_identitas'   => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'rincian_informasi' => 'required|string',
            'tujuan_penggunaan' => 'required|string',
            'cara_mendapatkan'  => 'required|in:email_download,ambil_langsung,pos',
        ];
    }

    public function messages(): array
    {
        return [
            'no_telepon.required'        => 'Nomor telepon wajib diisi.',
            'email.required'             => 'Email wajib diisi.',
            'email.email'                => 'Email tidak valid.',
            'alamat.required'            => 'Alamat wajib diisi.',
            'rincian_informasi.required' => 'Mohon jelaskan informasi yang Anda butuhkan.',
            'cara_mendapatkan.required'  => 'Pilih salah satu cara mendapatkan informasi.',
            'bukti_identitas.mimes'      => 'Bukti identitas harus berupa JPG, PNG, atau PDF.',
            'bukti_identitas.max'        => 'Ukuran file maksimal 5MB.',
            'tujuan_penggunaan.required' => 'Tujuan penggunaan informasi wajib diisi.',
        ];
    }
}
