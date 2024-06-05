<?php

namespace App\Http\Requests\Transaction\PenilaianDaftarUser;

use App\Models\Transaction\Penilaian;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            // 'user_id' => 'required',
            'users' => 'nullable',
            // 'ref_klp_id' => ['nullable', 'integer', 'exists:ref_klps,id'],
            'trans_penilaian_id' => ['required', 'integer', 'exists:' . (new Penilaian())->getConnectionName() . '.' . (new Penilaian())->getTable() . ',id'],
            'no_surat' => 'required|string|max:50',
            'surat_tugas' => 'required|file|mimes:pdf|max:2048',
            'tgl_surat' => 'required|date',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai_st',
        ];
    }
}
