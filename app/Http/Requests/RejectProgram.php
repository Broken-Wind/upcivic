<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectProgram extends FormRequest
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
            'reject_program_id' => 'required|numeric',
            'rejection_reason' => 'nullable|string',
        ];
    }
}
