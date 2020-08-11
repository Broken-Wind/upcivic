<?php

namespace Upcivic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Upcivic\Rules\Slug;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'slug' => ['required', 'unique:tenants', 'max:255'.'alpha_dash', new Slug],
        ];
    }
}
