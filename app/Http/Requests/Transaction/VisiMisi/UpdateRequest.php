<?php

namespace App\Http\Requests\Transaction\VisiMisi;

use App\Models\Transaction\VisiMisi;
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
        $rules = [
            'penilaian_id' => ['required', 'integer', 'exists:' . (new VisiMisi())->getConnectionName() . '.' . (new VisiMisi())->getTable() . ',id'],
        ];

        if ($this->filled('visi')) {
            $rules['visi'] = ['required', 'string'];
        }
        if ($this->filled('misi')) {
            $rules['misi'] = ['required', 'string'];
        }

        // Jika visi atau misi dikirim, pastikan keduanya berupa string
        if ($this->filled('visi') && $this->filled('misi')) {
            $rules = array_merge($rules, [
                'visi' => ['required', 'string'],
                'misi' => ['required', 'string'],
            ]);
        }

        return $rules;
    }
}
