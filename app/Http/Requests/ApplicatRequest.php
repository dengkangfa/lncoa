<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicatRequest extends FormRequest
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
            'principal' => 'required',
            'mobile' => 'required|regex:/^1[34578][0-9]{9}$/',
            'mechanism_id' => 'required',
            'number' => 'required|numeric|max:3000',
            'type_id' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
            'reason' => 'required',
        ];
    }
}
