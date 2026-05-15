<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIsiStatistikRequest extends FormRequest
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
            "group_kategori_item_id" => ["required", "exists:group_kategori_items,id"],
            "tahun" => ["required", "integer", "min:1900", "max:" . (date('Y') + 1)],
            "value" => ["required", "numeric", "min:0"]
        ];
    }

    public function messages(): array
    {
        return [
            "group_kategori_item_id.required" => "Group kategori item harus diisi.",
            "group_kategori_item_id.exists" => "Group kategori item yang dipilih tidak valid.",
            "tahun.required" => "Tahun harus diisi.",
            "tahun.integer" => "Tahun harus berupa angka.",
            "tahun.min" => "Tahun tidak boleh kurang dari 1900.",
            "tahun.max" => "Tahun tidak boleh lebih dari tahun depan.",
            "value.required" => "Nilai harus diisi.",
            "value.numeric" => "Nilai harus berupa angka.",
            "value.min" => "Nilai tidak boleh kurang dari 0."
        ];
    }
}
