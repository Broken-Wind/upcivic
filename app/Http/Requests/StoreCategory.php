<?php

namespace App\Http\Requests;

use App\Category;
use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategory extends FormRequest
{

    public function __construct(ValidationFactory $validationFactory)
    {
        /**
         * The built-in 'unique' validation rule would bypass our TenantOwnedCategoryScope,
         * testing uniqueness amongst every tenant's categories. Since we only want the category
         * name to be unique to the tenant, we need a custom rule.
         * */
        $validationFactory->extend(
            'unique_category',
            function ($attribute, $value, $parameters) {
                return Category::where('name', $value)->get()->isEmpty();
            },
            'A category with that name already exists.'
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
            'name' => 'required|string|unique_category'
        ];
    }
}
