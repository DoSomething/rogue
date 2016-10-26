<?php

namespace Rogue\Http\Requests;

class ReportbackItemRequest extends Request
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
        foreach ($this->request as $key => $value) {
            $rules[$key]['rogue_reportback_item_id'] = 'required';
            $rules[$key]['status'] = 'required';
        }

        return $rules;
    }
}


// array:2 [
//   "rogue_reportback_item_id" => "required"
//   "status" => "required"
// ]
// array:2 [
//   "rogue_reportback_item_id" => "254"
//   "status" => "approved"
// ]
