<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTemplate extends FormRequest
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
            'invoice_type' => 'required|string|max:255',
            'invoice_amount' => 'required|numeric',
            'meeting_interval' => 'required|numeric|max:7',
            'meeting_minutes' => 'required|numeric|max:1440',
            'meeting_count' => 'required|numeric|max:100',
            'min_enrollments' => 'nullable|numeric|between:0,9999',
            'max_enrollments' => 'nullable|numeric|between:0,9999',
            'enrollment_message' => 'nullable|string',
            'category_id' => 'exclude_unless:has_categories,true|required|numeric',
        ];
    }
}
