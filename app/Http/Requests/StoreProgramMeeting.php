<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramMeeting extends FormRequest
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
            'site_id' => 'nullable|numeric',
            'start_datetime' => 'required|date_format:"Y-m-d\TH:i"',
            'end_datetime' => 'required|date_format:"Y-m-d\TH:i"',
            'note' => 'nullable|string|max:255',
        ];
    }
}
