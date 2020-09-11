<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveProgram extends FormRequest
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
            'approve_program_id' => 'required|numeric',
            'contributor_id' => 'required|alpha_dash', //may contain the string 'approve_all'
            'approve_next_steps' => 'nullable',
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'approve_program_id.required' => 'No program was selected.',
            'contributor_id.required' => 'No contributor was selected.',
        ];
    }
}
