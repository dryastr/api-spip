<?php

namespace App\Http\Requests\Registration;

use App\Models\Referensi\Klp;
use App\Models\Referensi\Lokasi;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
            'jenis' => ['required', 'in:KL,PEMDA,OPD'],
            'ref_klp_id' => [
                'nullable',
                'integer',
                'exists:' . (new Klp())->getConnectionName() . '.' . (new Klp())->getTable() . ',id',
            ],
            'ref_klp_nama' => ['nullable', 'string'],
            'no_telp' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'string', 'max:50'],
            // 'fax' => ['nullable', 'string', 'max:50'],
            'alamat' => ['nullable', 'string'],
            'file_surat_permohonan' => ['required', 'file'],
            'file_sk_penunjukan_admin' => 'required|file|mimes:pdf|max:2048',
            'email' => ['required', 'string'],
            'fullname' => ['required', 'string', 'max:150'],
            'password' => ['required', 'string'],
            'ref_lokasi_id' => ['required_if:jenis,OPD', 'integer', 'exists:' . (new Lokasi())->getConnectionName() . '.' . (new Lokasi())->getTable() . ',id'],
            'ref_lokasi_kabkot_id' => ['required_if:jenis,OPD', 'integer', 'exists:' . (new Lokasi())->getConnectionName() . '.' . (new Lokasi())->getTable() . ',id'],
            'nama_dinas' => ['required_if:jenis,OPD', 'string'],
        ];
    }
}
