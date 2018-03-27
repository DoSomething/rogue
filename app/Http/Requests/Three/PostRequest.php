<?php

namespace Rogue\Http\Requests\Three;

use Rogue\Http\Requests\Request;

class PostRequest extends Request
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
            'campaign_id' => 'required',
            'campaign_run_id' => 'integer',
            'northstar_id' => 'nullable|objectid',
            'type' => 'required|string|in:photo,voter-reg,text',
            'action' => 'required|string',
            'why_participated' => 'nullable|string',
            'text' => 'required|nullable|string|max:255',
            'quantity' => 'nullable|integer',
            'file' => 'image|dimensions:min_width=400,min_height=400',
            'status' => 'in:pending,accepted,rejected',
            'details'=> 'json',
        ];
    }
}
