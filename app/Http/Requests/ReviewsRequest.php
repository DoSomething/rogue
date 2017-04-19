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
            'postable_id' => 'required',
            'postable_type' => 'required',
            'status' => 'required',
            'admin_northstar_id' => 'required',
        ];
    }
}
