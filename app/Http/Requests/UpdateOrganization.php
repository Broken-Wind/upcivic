<?php

namespace Upcivic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Auth;
use Illuminate\Validation\Factory;

class UpdateOrganization extends FormRequest
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



    public function validator(Factory $factory)
    {
        $validator = $factory->make($this->input(), $this->rules());
        $validator->sometimes('name', 'unique:organizations', function($input) {
            return $input->name !== tenant()['name'];
        });
        return $validator;
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
            'name' => 'required|max:255',
            'publish' => 'nullable|boolean',
        ];
    }
}
