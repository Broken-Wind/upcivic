<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePublicFile extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'files' => 'required|array',
            'files.*' => 'required|mimes:pdf,doc,docx,csv,xls,xlsx,jpeg,jpg,png|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'files.*.max' => 'The file size may not be greater than 2MB.',
        ];
    }
}
