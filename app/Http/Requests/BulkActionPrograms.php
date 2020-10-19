<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkActionPrograms extends FormRequest
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
            'program_ids' => 'required|array',
            'program_ids.*' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'program_ids.required' => 'Select at least one program.',
            'program_ids.*.required' => 'Select at least one program.',
        ];
    }

}
