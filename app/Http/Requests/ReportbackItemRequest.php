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
        // here we're just sending an array, do we need rules for this?
        // return [
        //     'rogue_reportback_item_id' => 'required|int',
        //     'quantity' => 'int',
        //     'why_participated' => 'string',
        //     'caption' => 'string',
        //     'source' => 'string',
        //     'status' => 'string',
        // ];
    }
}
