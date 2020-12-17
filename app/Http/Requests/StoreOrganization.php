<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganization extends FormRequest
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
            'name' => 'required|unique:organizations|max:255',
            'administrator' => 'nullable|array',
            'administrator.first_name' => 'required|string',
            'administrator.last_name' => 'required|string',
            'administrator.email' => 'required|email',
            'administrator.title' => 'nullable|string',
            'enrollment_url' => 'nullable|string',
        ];
    }
}
