<?php

namespace Upcivic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramMeetings extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'meeting_notes.*' => 'nullable|string|max:255',
            'shift_meetings' => 'nullable|numeric',

        ];
    }
}
