<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowInstructor extends FormRequest
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
            'show_all' => 'nullable|numeric'
        ];
    }
}
