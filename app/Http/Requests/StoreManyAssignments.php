<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreManyAssignments extends FormRequest
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
            'task_id' => 'required|numeric',
            'organization_ids' => 'required|array',
            'organization_ids.*' => 'required|numeric',
            'organization_program_ids' => 'exclude_unless:should_associate_programs,true|required|array',
            'organization_program_ids.*' => 'exclude_unless:should_associate_programs,true|required|array',
            'organization_program_ids.*.*' => 'exclude_unless:should_associate_programs,true|required|numeric',
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
            'organization_ids.required' => 'You must include at least one organization.',
            'organization_ids.*.required' => 'You must include at least one organization.',
            'organization_program_ids.required' => 'You must include at least one program for each organization.',
            'organization_program_ids.*.required' => 'You must include at least one program for each organization.',
            'organization_program_ids.*.*.required' => 'You must include at least one program for each organization.',
        ];
    }
}
