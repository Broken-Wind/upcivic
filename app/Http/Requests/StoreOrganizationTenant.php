<?php

namespace App\Http\Requests;

use App\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationTenant extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ! $this->user()->hasTenant();
    }

    public function messages()
    {
        return [
            'slug.required' => 'A vanity URL is required.',
            'slug.unique:organizations' => 'That vanity URL is already taken. Please choose another.',
            'slug.max:255' => 'Please choose a shorter vanity URL.',
            'slug.alpha_dash' => 'Your vanity URL must only contain letters, numbers, and dashes.',

        ];
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
            'slug' => ['required', 'unique:tenants', 'max:255', 'alpha_dash', new Slug],
        ];
    }
}
