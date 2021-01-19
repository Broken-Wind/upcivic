<?php

namespace App\Http\Requests;

use App\Area;
use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Foundation\Http\FormRequest;

class StoreArea extends FormRequest
{

    public function __construct(ValidationFactory $validationFactory)
    {
        /**
         * The built-in 'unique' validation rule would bypass our TenantOwnedAreaScope,
         * testing uniqueness amongst every tenant's areas. Since we only want the area
         * name to be unique to the tenant, we need a custom rule.
         * */
        $validationFactory->extend(
            'unique_area',
            function ($attribute, $value, $parameters) {
                return Area::where('name', $value)->get()->isEmpty();
            },
            'An area with that name already exists.'
        );

    }
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
            'name' => 'required|string|unique_area'
        ];
    }
}
