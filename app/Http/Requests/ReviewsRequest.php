<?php

namespace Rogue\Http\Requests;

class ReviewsRequest extends Request
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
            'rogue_event_id' => 'required',
            'status' => 'required',
            'reviewer' => 'required',
            'event_type' => 'required',
        ];
    }
}
