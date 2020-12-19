<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTask extends FormRequest
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
            'name' => 'required|string',
            'description' => 'required|string',
            'files' => 'nullable|array',
            'files.*' => 'nullable|mimes:pdf,docx,jpeg,jpg,png|max:2048',
            'documentTitle' => 'nullable|string',
            'documentContent' => 'nullable|string'
        ];
    }
}
