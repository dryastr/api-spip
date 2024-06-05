<?php

namespace App\Http\Requests\Transaction\Penilaian;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            // 'ref_klp_id' => ['required', 'integer', 'exists:' . (new Klp())->getConnectionName() . '.' . (new Klp())->getTable() . ',id'],
            'tahun' => ['required', 'integer'],
            'anggaran' => ['required', 'numeric'],
        ];
    }
}
