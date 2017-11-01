<?php

namespace Rogue\Http\Requests\Three;

use Rogue\Http\Requests\Request;

class SignupUpdateRequest extends Request
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
            'quantity' => 'required_without_all:why_participated',
            'why_participated' => 'required_without_all:quantity',
        ];
    }
}
