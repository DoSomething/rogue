<?php

namespace Rogue\Http\Requests;

class ReactionRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reportback_item_id' => 'required|int',
            'northstar_id' => 'required|int',
        ];
    }
}
