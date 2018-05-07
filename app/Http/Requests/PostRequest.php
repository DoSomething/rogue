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
            'campaign_id' => 'required',
            'campaign_run_id' => 'integer',
            'northstar_id' => 'nullable|objectid',
            'type' => 'required|string|in:photo,voter-reg,text',
            'action' => 'required|string',
            'why_participated' => 'nullable|string',
            'text' => 'nullable|string|max:256',
            'quantity' => 'nullable|integer',
            'file' => 'image|dimensions:min_width=400,min_height=400,max_width=5000,max_height_3500',
            'status' => $this->getStatusRules($this->type),
            'details'=> 'json',
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
            'text' => 'nullable|string|max:140',
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
                $rule = 'in:register-form,register-OVR,confirmed,ineligible,uncertain';
                break;
            default:
                $rule = 'in:pending,accepted,rejected';
        }

        return $rule;
    }
}
