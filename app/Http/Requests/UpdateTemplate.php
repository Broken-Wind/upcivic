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
            'name' => 'required|string|max:255',
            'internal_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'ages_type' => 'required|string|alpha|max:10',
            'min_age' => 'required|numeric|between:0,999',
            'max_age' => 'required|numeric|between:0,999',
            'invoice_type' => 'required|string|max:255',
            'invoice_amount' => 'required|numeric',
            'meeting_interval' => 'required|numeric|max:7',
            'meeting_minutes' => 'required|numeric|max:1440',
            'meeting_count' => 'required|numeric|max:100',
        ];
    }
}
