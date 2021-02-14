<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgram extends FormRequest
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
            'name' => 'required|string|max:255',
            'internal_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'public_notes' => 'nullable|string',
            'contributor_notes' => 'nullable|string',
            'ages_type' => 'required|string|alpha|max:10',
            'min_age' => 'required|numeric|between:0,999',
            'max_age' => 'required|numeric|between:0,999',
        ];
    }
}
