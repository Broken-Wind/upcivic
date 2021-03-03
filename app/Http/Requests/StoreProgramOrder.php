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
            'participants.*.needs' => 'nullable|string',
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
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'stripeEmail.required' => 'You must enter a valid email address.',
            'stripeEmail.email' => 'You must enter a valid email address.',
            'ticket_quantity.required' => 'You must enroll at least one participant.',
            'ticket_quantity.min:1' => 'You must enroll at least one participant.',
            'stripeToken:required' => 'Payment token error. Please contact ' . config('mail.support_email') . 'for assistance.',
            'stripeToken:string' => 'Payment token error. Please contact ' . config('mail.support_email') . 'for assistance.',
            'participants.required' => 'You must enroll at least one participant.',
            'participants.array' => 'You must enroll at least one participant.',
            'participants.*.required' => 'You must enroll at least one participant.',
            'participants.*.array' => 'You must enroll at least one participant.',
            'participants.*.first_name.required' => 'The participant first name is required.',
            'participants.*.first_name.string' => 'The participant first name is required.',
            'participants.*.last_name.required' => 'The participant last name is required.',
            'participants.*.last_name.string' => 'The participant last name is required.',
            'participants.*.birthday.required' => 'The participant birthday is required.',
            'participants.*.birthday.string' => 'The participant birthday is required.',
            'primary_contact.required' => 'The primary contact is required.',
            'primary_contact.string' => 'The primary contact is required.',
            'primary_contact.first_name.required' => 'The primary contact first name is required.',
            'primary_contact.first_name.string' => 'The primary contact first name is required.',
            'primary_contact.last_name.required' => 'The primary contact last name is required.',
            'primary_contact.last_name.string' => 'The primary contact last name is required.',
            'primary_contact.phone.required' => 'The primary contact phone number is required.',
            'primary_contact.phone.string' => 'The primary contact phone number is required.',
        ];
    }
}
