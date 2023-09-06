<?php

namespace App\Http\Requests;

use App\Models\PlanSetting;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
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
        $freeDownline = PlanSetting::getFreeDownlineCount();
        return [
            'downline_number'   =>  "required|integer|min:{$freeDownline}",
            'plan_coupon'   =>  "string|nullable"
        ];
    }
}
