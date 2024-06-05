<?php

namespace App\Http\Requests\Transaction\DataOpini;

use App\Models\Referensi\Klp;
use App\Models\Transaction\Penilaian;
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

    public function prepareForValidation()
    {
        $this->merge([
            'ref_klp_id' => $this->headers->get('X-Klp-Id')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'ref_klp_id' => ['required', 'integer', 'exists:' . (new Klp())->getConnectionName() . '.' . (new Klp())->getTable() . ',id'],
            'trans_penilaian_id' => ['required', 'integer', 'exists:' . (new Penilaian())->getConnectionName() . '.' . (new Penilaian())->getTable() . ',id'],
            'tahun' => ['required', 'integer'],
            'opini' => ['required', 'string'],
            'persentase_bmn' => ['required', 'integer'],
        ];
    }
}