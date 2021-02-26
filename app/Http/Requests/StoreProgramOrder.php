<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'stripeEmail' => 'required|email',
            'ticket_quantity' => 'required|integer|min:1',
            'stripeToken' => 'required|string',
            'participants' => 'required|array',
            'participants.*' => 'required|array',
            'participants.*.first_name' => 'required|string',
            'participants.*.last_name' => 'required|string',
            'participants.*.birthday' => 'required|string',
            'primary_contact' => 'required|array',
            'primary_contact.first_name' => 'required|string',
            'primary_contact.last_name' => 'required|string',
            'primary_contact.phone' => 'required|string',
            'alternate_contact' => 'nullable|array',
            'alternate_contact.first_name' => 'nullable|string',
            'alternate_contact.last_name' => 'nullable|string',
            'alternate_contact.phone' => 'nullable|string',
        ];
    }
}
