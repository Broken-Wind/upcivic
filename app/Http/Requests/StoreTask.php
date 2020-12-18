<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTask extends FormRequest
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
            'assignToEntity' => 'required|string|in:App\Organization,App\Instructor',
            'files' => 'nullable|array',
            'files.*' => 'nullable|mimes:pdf,docx,jpeg,jpg,png|max:2048',
            'isDocument' => 'nullable|boolean',
            'documentTitle' => 'nullable|string',
            'documentText' => 'nullable|string'
        ];
    }
}
