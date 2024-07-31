<?php

namespace App\Http\Requests\API;

use App\Models\CostSummaryGroup;
use InfyOm\Generator\Request\APIRequest;

class UpdateCostSummaryGroupAPIRequest extends APIRequest
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
        $rules = CostSummaryGroup::$rules;
        
        return $rules;
    }
}
