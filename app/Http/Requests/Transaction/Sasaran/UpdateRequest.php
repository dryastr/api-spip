<?php

namespace App\Http\Requests\Transaction\Sasaran;

use App\Models\Referensi\JenisSasaran;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'ref_jenis_sasaran_id' => ['required', 'integer', 'exists:' . (new JenisSasaran())->getConnectionName() . '.' . (new JenisSasaran())->getTable() . ',id'],
            'kode' => ['required', 'string', 'max:50'],
            'nama' => ['required', 'string', 'max:200'],
            'nsa_orientasi_hasil' => ['nullable', 'boolean'],
            'nsa_relevan_mandat_sa' => ['nullable', 'boolean'],
            'nsa_uji_kecukupan_indikator_sa' => ['nullable', 'boolean'],
            'catatan' => ['nullable','string'],
        ];
    }
}
