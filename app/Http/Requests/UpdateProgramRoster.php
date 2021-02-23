<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramRoster extends FormRequest
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
        $program = $this->route('program');
        return [
            //
            'price' => [
                'nullable',
                'numeric',
                function ($attribute, $value, $fail) {
                    if (
                        empty($value)
                        && $this->route('program')->getContributorFor(tenant())->allowsRegistration()
                        && $this->route('program')->canUpdateEnrollmentsBy(tenant())
                    ) {
                        $fail('You must set a price.');
                    }
                }
            ],
            'enrollment_url' => 'nullable|string',
            'enrollment_instructions' => 'nullable|string',
            'min_enrollments' => [
                'nullable',
                'numeric',
                'between:0,9999',
                'lte:max_enrollments',
                function ($attribute, $value, $fail) {
                    if (
                        $value == null
                        && !$this->route('program')->getContributorFor(tenant())->allowsRegistration()
                        && $this->route('program')->canUpdateEnrollmentsBy(tenant())
                    ) {
                        $fail('You must include the minimum enrollments.');
                    }
                }
            ],
            'enrollments' => [
                'nullable',
                'numeric',
                'between:0,9999',
                function ($attribute, $value, $fail) {
                    if (
                        $value == null
                        && !$this->route('program')->getContributorFor(tenant())->allowsRegistration()
                        && $this->route('program')->canUpdateEnrollmentsBy(tenant())
                    ) {
                        $fail('You must include the number of current enrollments.');
                    }
                }
            ],
            'max_enrollments' => [
                'nullable',
                'numeric',
                'between:0,9999',
                'gte:min_enrollments',
                function ($attribute, $value, $fail) {
                    if (
                        $value == null
                        && !$this->route('program')->getContributorFor(tenant())->allowsRegistration()
                        && $this->route('program')->canUpdateEnrollmentsBy(tenant())
                    ) {
                        $fail('You must include the maximum enrollments.');
                    }
                }
            ],
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
            'min_enrollments.lte' => 'The minimum enrollments must be less than or equal to the maximum enrollments.',
            'max_enrollments.gte' => 'The maximum enrollments must be greater than or equal to the current enrollments.',
        ];
    }
}
