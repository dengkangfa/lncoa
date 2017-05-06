<?php

namespace App\Http\Requests;

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
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'required|unique:users|regex: /^[\\x{4e00}-\\x{9fa5}_a-zA-Z0-9-]{2,16}$/u',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|max:16|min:5',
            'password_confirmation' => 'required',
        ];
    }
}
