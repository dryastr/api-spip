<?php

namespace App\Http\Requests\User;

// use App\Models\Referensi\Klp;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'mobile' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string'],
            'fullname' => ['required', 'string', 'max:150'],
            'avatar' => ['required', 'file'],
            'password' => ['required', 'string'],
        ];
    }
}
