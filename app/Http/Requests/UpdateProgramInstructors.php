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
                Rule::in(['add_all', 'add_selected', 'remove_all', 'remove_selected'])
            ],
            'instructor_id' => 'required|numeric',
            'meeting_ids' => 'required|array',
            'meeting_ids.*' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'instructor_id.required' => 'You must select an instructor.',
            'instructor_id.numeric' => 'You must select an instructor.',
            'meeting_ids.array' => 'Instructor assignment error',
            'meeting_ids.*.numeric' => 'Instructor assignment error',
            'meeting_ids.required' => 'You must select at least one meeting.',
            'meeting_ids.*.required' => 'You must select at least one meeting.',
        ];
    }
}
