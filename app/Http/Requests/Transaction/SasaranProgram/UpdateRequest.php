<?php

namespace App\Http\Requests\Transaction\SasaranProgram;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trans_program_id' => ['required', 'integer', 'exists:trans_program,id'],
            'trans_sasaran_id' => ['required', 'integer', 'exists:trans_sasaran,id'],
            'trans_sasaran_indikator_id' => ['required', 'integer', 'exists:trans_sasaran_indikator,id'],
        ];
    }
}
