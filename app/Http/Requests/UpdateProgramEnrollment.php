<?php

namespace Upcivic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramEnrollment extends FormRequest
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
            'enrollments' => 'nullable|numeric|between:0,9999',
            'max_enrollments' => 'nullable|numeric|between:0,9999',
        ];
    }
}