<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'code' => 'required',
            'description' => 'required|string',
            'expiration' => 'required',  
            'discount_percentage' => 'required_without:discount_amount',
            'discount_amount' => 'required_without:discount_percentage'
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(){
        return [
            'code.required' => __('Code is required'),
            'description.required' => __('Description is required'),
            'expiration.required' => __('Expiration is required'),
            'discount_percentage.required_without' => __('Discount Percentage is required when discount amount is not present'),
            'discount_amount.required_without' => __('Discount Amount is required is required when discount percentage is not present')
        ];
    }
}
