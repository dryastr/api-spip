<?php

namespace App\Http\Requests\Referensi\KategoriRisiko;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'ref_klp_id' => ['nullable', 'integer', 'exists:ref_klps,id'],
            'kode' => ['nullable', 'string', 'max:50'],
            'nama' => ['nullable', 'string', 'max:255'],
        ];
    }
}
