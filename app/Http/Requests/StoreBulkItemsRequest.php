<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBulkItemsRequest extends FormRequest
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
            'group_ids'   => 'required|array|min:1',
            'group_ids.*' => 'required|exists:group_kategoris,id',
            'items'       => 'required|array|min:1',
            'items.*'     => 'required|string|max:100',
        ];
    }
}
