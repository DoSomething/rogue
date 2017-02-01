<?php

namespace Rogue\Http\Requests;

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
                'event_type' => 'required|in:post_photo,review_photo',
            ];
        }

        return $this->getRules($this->request);
    }

    /**
     * Determines the validation ruleset to use for a particular type of post.
     *
     * @param  object $request
     * @return array|null
     */
    protected function getRules($request)
    {
        switch ($request->event_type) {
            case 'post_photo':
                return array_merge($this->rules, $this->getPhotoRules());

                break;

            default:
                break;
        }
    }

    /**
     * Defines the ruleset for photo posts.
     *
     * @return array
     */
    protected function getPhotoRules()
    {
        return [
            'caption' => 'string',
            'status' => 'pending',
            'source' => 'string',
            'remote_addr' => 'ip',
            'file' => 'string', // @TODO - should do some better validation around files.
        ];
    }
}
