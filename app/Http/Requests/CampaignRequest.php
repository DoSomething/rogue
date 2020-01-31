<?php

namespace Rogue\Http\Requests;

class CampaignRequest extends Request
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
        if ($this->method() === 'PATCH') {
            return $this->patchRules();
        }

        return [
            //not returning any validation rules right now because we are limiting this to patch requests on campaigns
        ];
    }

    /**
     * Get the validation rules that apply to PATCH requests.
     *
     * @return array
     */
    private function patchRules()
    {
        return [
            'contentful_campaign_id' => 'nullable|string|max:255',
        ];
    }
}
