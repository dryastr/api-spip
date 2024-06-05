<?php

namespace App\Http\Requests\Transaction\SasaranIndikator;

use App\Models\Transaction\Sasaran;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'trans_sasaran_id' => ['required', 'integer', 'exists:' . (new Sasaran())->getConnectionName() . '.' . (new Sasaran())->getTable() . ',id'],
            'kode' => ['required', 'string', 'max:50'],
            'nama' => ['required', 'string', 'max:200'],
            'target_kinerja' => ['required', 'integer'],
            'satuan' => ['required', 'string'],
            'nsa_indikator_kinerja_tepat' => ['required', 'boolean'],
            'nsa_target_kinerja_tepat' => ['required', 'boolean'],
        ];
    }
}
