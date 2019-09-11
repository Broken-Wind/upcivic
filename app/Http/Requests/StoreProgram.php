<?php

namespace Upcivic\Http\Requests;

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
            'programs.*' => 'required|array',
            'programs.*.template_id' => 'nullable|numeric',
            'programs.*.start_date' => 'nullable|date',
            'programs.*.start_time' => 'nullable|string',
            'programs.*.ages_type' => 'nullable|string|alpha|max:10',
            'programs.*.min_age' => 'nullable|numeric|between:0,999',
            'programs.*.max_age' => 'nullable|numeric|between:0,999',
        ];
    }
}
