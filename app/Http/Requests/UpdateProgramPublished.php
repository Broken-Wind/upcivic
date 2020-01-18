<?php

namespace Upcivic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramPublished extends FormRequest
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
            'publish_now' => 'nullable',
            'published_at' => 'nullable|date_format:"Y-m-d"',
        ];
    }
}
