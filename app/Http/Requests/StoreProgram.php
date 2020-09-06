<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgram extends FormRequest
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
            'recipient_organization_id' => 'required|numeric',
            'site_id' => 'nullable|numeric',
            'template_id' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'start_time' => 'required|string',
            'end_time' => 'nullable|string',
            'ages_type' => 'nullable|string|alpha|max:10',
            'min_age' => 'nullable|numeric|between:0,999',
            'max_age' => 'nullable|numeric|between:0,999',
            'cc_emails.*' => 'nullable|email',
            'cc_emails' => 'nullable|array',

        ];
    }
}
