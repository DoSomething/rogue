<?php

namespace Rogue\Http\Requests\Legacy\Two;

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
            'northstar_id' => 'required|string',
            'campaign_id' => 'required',
            'campaign_run_id' => 'int',
            'quantity' => 'nullable|int',
            'caption' => 'nullable|string',
            'status' => 'in:pending,accepted,rejected',
            'source' => 'nullable|string',
            'file' => 'dimensions:min_width=400,min_height=400,max_width=5000,max_height_4000',
            'type' => 'string|in:photo,voter-reg,text',
            'action' => 'string',
        ];
    }
}
