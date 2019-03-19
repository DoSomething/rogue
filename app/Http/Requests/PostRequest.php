<?php

namespace Rogue\Http\Requests;

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
        if ($this->method() === 'PATCH') {
            return $this->patchRules();
        }

        return [
            'campaign_id' => 'required_without:action_id|integer',
            'northstar_id' => 'nullable|objectid',
            'type' => 'required|string|in:photo,voter-reg,text,share-social,phone-call',
            // @TODO: eventually, deprecate action in the payload and make action_id required when all systems have been updated.
            'action' => 'required_without:action_id|string',
            'action_id' => 'required_without:action,campaign_id|integer|exists:actions,id',
            'why_participated' => 'nullable|string',
            'text' => 'nullable|string|max:500',
            'location' => 'nullable|iso3166',
            'quantity' => 'nullable|integer',
            'file' => 'image|dimensions:min_width=400,min_height=400,max_width=5000,max_height_4000',
            'status' => $this->getStatusRules($this->type),
            'details' => 'json',
            'created_at' => 'date',
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
            'text' => 'nullable|string|max:500',
            'location' => 'nullable|iso3166',
            'quantity' => 'nullable|integer',
            'status' => $this->getStatusRules($this->post->type),
        ];
    }

    /**
     * Get the allowed statuses for the type of post
     *
     * @param string $type
     * @return string
     */
    private function getStatusRules($type)
    {
        switch ($type) {
            case 'photo':
                $rule = 'in:pending,accepted,rejected';
                break;
            case 'voter-reg':
                $rule = 'in:pending,register-form,register-OVR,confirmed,ineligible,uncertain';
                break;
            case 'phone-call':
                $rule = 'in:accepted,incomplete';
                break;
            default:
                $rule = 'in:pending,accepted,rejected';
        }

        return $rule;
    }
}
