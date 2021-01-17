<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramInstructors extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->memberOfTenant(tenant());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'action' => [
                'required',
                'string',
                Rule::in(['add_instructor', 'remove_instructor'])
            ],
            'instructor_id' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'instructor_id.required' => 'You must select an instructor.',
            'instructor_id.numeric' => 'You must select an instructor.',
        ];
    }
}
