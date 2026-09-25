<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermohonanKeberatanRequest extends FormRequest
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
            'nomor_registrasi_asal' => 'nullable|string|exists:permohonan,nomor_registrasi',
            'nama_lengkap'          => 'nullable|string|max:255',
            'alamat_lengkap'        => 'nullable|string',
            'email'                 => 'nullable|email:rfc,dns|max:255',
            'pekerjaan'             => 'nullable|string|max:255',
            'no_telepon'            => 'nullable|string|max:30',
            'alamat'                => 'nullable|string',
            'alasan_keberatan'      => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_registrasi_asal.required' => 'Nomor registrasi permohonan awal wajib diisi.',
            'nomor_registrasi_asal.exists'    => 'Nomor registrasi tidak ditemukan.',
            'email.email'                     => 'Alamat email tidak valid, mohon periksa kembali.',
            'alasan_keberatan.required'       => 'Mohon jelaskan alasan keberatan Anda.',
        ];
    }
}
