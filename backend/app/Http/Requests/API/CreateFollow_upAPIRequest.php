<?php

namespace App\Http\Requests\API;

use App\Models\follow_up;
use InfyOm\Generator\Request\APIRequest;

class CreateFollow_upAPIRequest extends APIRequest
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
        return follow_up::$rules;
    }
}
