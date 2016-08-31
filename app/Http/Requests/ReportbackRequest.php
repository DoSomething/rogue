<?php

namespace Rogue\Http\Requests;

use Rogue\Http\Requests\Request;

class ReportbackRequest extends Request
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
            'northstar_id' => 'required_without:drupal_id',
            'drupal_id' => 'required_without:northstar_id',
            'campaign_id' => 'required|int',
            'campaign_run_id' => 'required|int',
            'quantity' => 'int',
            'why_participated' => 'string',
            'caption' => 'string',
            'source' => 'string',
        ];
    }
}
