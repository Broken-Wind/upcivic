<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory;

class UpdateTenant extends FormRequest
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

    public function validator(Factory $factory)
    {
        $validator = $factory->make($this->input(), $this->rules());
        $validator->sometimes('name', 'unique:organizations', function ($input) {
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
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'publish' => 'nullable|boolean',
            'proposal_next_steps' => 'nullable|string',
        ];
    }
}
