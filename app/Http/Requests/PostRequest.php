<?php

namespace Rogue\Http\Requests;

use Rogue\Http\Requests\Request;

class PostRequest extends Request
{

    protected $rules = [
        'northstar_id' => 'required|string',
        'campaign_id' => 'required|int',
        'campaign_run_id' => 'required|int',
    ];

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
        if (! isset($this->request->event_type)) {
            return [
                'event_type' => 'required|in:post_photo',
            ];
        }

        return $this->getRules($this->request);
    }

    protected function getRules($request)
    {
        switch ($request->event_type) {
            case 'post_photo':
                return array_merge($this->rules, $this->setPhotoRules());

                break;

            default:
                break;
        }
    }

    protected function setPhotoRules()
    {
        return [
            'caption' => 'string',
            'status' => 'pending',
            'source' => 'string',
            'remote_addr' => 'ip',
        ];
    }
}
