<?php

namespace App\Http\Requests\Transaction\Sasaran;

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
            'nsa_orientasi_hasil' => $this->nsa_orientasi_hasil ? 1 : 0,
            'nsa_relevan_mandat_sa' => $this->nsa_relevan_mandat_sa ? 1 : 0,
            'nsa_uji_kecukupan_indikator_sa' => $this->nsa_uji_kecukupan_indikator_sa ? 1 : 0,
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
            'nsa_orientasi_hasil' => ['nullable', 'boolean'],
            'nsa_relevan_mandat_sa' => ['nullable', 'boolean'],
            'nsa_uji_kecukupan_indikator_sa' => ['nullable', 'boolean'],
        ];
    }
}
