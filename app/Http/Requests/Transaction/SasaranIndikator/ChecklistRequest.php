<?php

namespace App\Http\Requests\Transaction\SasaranIndikator;

use Illuminate\Foundation\Http\FormRequest;

class ChecklistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'nsa_indikator_kinerja_tepat' => $this->nsa_indikator_kinerja_tepat ? 1 : 0,
            'nsa_target_kinerja_tepat' => $this->nsa_target_kinerja_tepat ? 1 : 0,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nsa_indikator_kinerja_tepat' => ['required', 'boolean'],
            'nsa_target_kinerja_tepat' => ['required', 'boolean'],
        ];
    }
}
