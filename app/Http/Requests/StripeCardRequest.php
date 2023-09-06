<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StripeCardRequest extends FormRequest
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
            'card_holder_name'  => 'required|max:255',
            'card_number'       => 'required|max:18',
            'cvv'               => 'required|min:3|max:4',
            'expiry_date'       => 'required',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'card_holder_name.required' => __('Name required'),
            'card_number.required' => __('Card Number is required'),
            'cvv.required' => __('CVV is required'),
            'cvv.max' => __('Invalid cvv'),
            'cvv.min' => __('Invalid cvv'),
            'expiry_date.required' => __('Expiry date is required'),
        ];
    }
}
