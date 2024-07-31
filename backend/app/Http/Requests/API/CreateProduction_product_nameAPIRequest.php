<?php

namespace App\Http\Requests\API;

use App\Models\Production_product_name;
use InfyOm\Generator\Request\APIRequest;

class CreateProduction_product_nameAPIRequest extends APIRequest
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
        return Production_product_name::$rules;
    }
}
