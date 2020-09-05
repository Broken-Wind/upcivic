<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramContributors extends FormRequest
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
            'contributors' => 'required|array',
            'contributors.*' => 'required|array',
            'contributors.*.invoice_type' => 'nullable|string|max:255',
            'contributors.*.invoice_amount' => 'nullable|numeric',

            'newContributor.organization_id' => 'nullable|numeric',
            'newContributor.invoice_type' => 'nullable|string|max:255',
            'newContributor.invoice_amount' => 'nullable|numeric',

        ];
    }
}
