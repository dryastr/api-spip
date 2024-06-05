<?php

namespace App\Http\Requests\Referensi\KkLeadSpip;

use App\Models\Referensi\JenisLeadSpip;
use App\Models\Referensi\KkLeadSpip;
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
            'parent_id' => ['nullable', 'integer', 'exists:' . (new KkLeadSpip())->getConnectionName() . '.' . (new KkLeadSpip())->getTable() . ',id'],
            'ref_jenis_kk_lead_spip_id' => ['nullable', 'integer', 'exists:' . (new JenisLeadSpip())->getConnectionName() . '.' . (new JenisLeadSpip())->getTable() . ',id'],
            'kode' => ['required', 'string', 'max:50'],
            'nama' => ['required', 'string', 'max:200'],
            'bobot' => ['required', 'numeric'],
            'jenis_klp' => ['required', 'in:PEMDA,KL'],
        ];
    }
}
