<?php

namespace App\Http\Requests\Referensi\Klp;

use App\Models\Referensi\Lokasi;
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
            'ref_lokasi_id' => ['required', 'integer', 'exists:' . (new Lokasi())->getConnectionName() . '.' . (new Lokasi())->getTable() . ',id'],
            'kode' => ['required', 'string', 'max:50'],
            'nama' => ['required', 'string', 'max:200'],
            'nama_pendek' => ['required', 'string', 'max:50'],
            'pimpinan' => ['required', 'string', 'max:100'],
            'jabatan_pimpinan' => ['required', 'string', 'max:100'],
            'jenis' => ['required', 'in:PS,KL,PEMDA,OPD'],
            'level' => ['required', 'in:PUSAT,NON-PUSAT'],
            'lokasi' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg|max:5120'],
            'no_telp' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'string', 'max:50'],
            'fax' => ['nullable', 'string', 'max:50'],
            'alamat' => ['required', 'string'],
        ];
    }
}
