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
        return $this->user()->memberOf(tenant());
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
            'organization_id' => 'required|numeric',
            'site_id' => 'nullable|numeric',
            'templates.*' => 'nullable|numeric',
            'start_dates.*' => 'nullable|date',
            'start_times.*' => 'nullable|string',
            'ages_types.*' => 'nullable|string|alpha|max:10',
            'min_ages.*' => 'nullable|numeric|between:0,999',
            'max_ages.*' => 'nullable|numeric|between:0,999',
        ];
    }
}
