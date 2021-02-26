<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailParticipants extends FormRequest
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
            'subject' => 'required|string|min:5',
            'message' => 'required|string|min:5',
            'contact_ids' => 'nullable|array',
            'contact_ids.*' => 'nullable|numeric',
            'cc_address_1' => 'nullable|email',
            'cc_address_2' => 'nullable|email',
        ];
    }
}
