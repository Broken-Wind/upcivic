<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRegistrationOptions extends FormRequest
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
            'internal_registration' => 'nullable|numeric',
            'price' => 'exclude_unless:internal_registration,1|required|numeric',
            'enrollment_url' => 'nullable|string',
            'enrollment_instructions' => 'nullable|string'
        ];
    }
}
