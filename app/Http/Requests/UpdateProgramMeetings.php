<?php

namespace Upcivic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory;

class UpdateProgramMeetings extends FormRequest
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

    public function messages()
    {
        return [

            'meeting_ids.required' => 'You must select at least one meeting.',

        ];
    }

    public function validator(Factory $factory)
    {
        $validator = $factory->make($this->input(), $this->rules(), $this->messages());
        $validator->sometimes('meeting_ids', 'required', function ($input) {
            return $input->update_all == null;
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
            'meeting_ids' => 'nullable|array',
            'meeting_ids.*' => 'nullable|numeric',
            'site_id' => 'nullable|numeric',
            'start_time' => 'nullable|date_format:"H:i"',
            'end_time' => 'nullable|date_format:"H:i"',
            'meeting_ids' => 'nullable|array',
            'shift_meetings' => 'nullable|numeric',

        ];
    }
}
