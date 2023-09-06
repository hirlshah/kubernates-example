<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProspectionVideoVisiterRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ];
    }

        /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(){
        return [
            'first_name.required' => __('First name is required'),
            'last_name.required' => __('Last name is required'),
            'email.required' => __('Email is required'),
            'email.email' => __('Enter valid email'),
            'phone.required' => __('Phone number is required'),
            'phone.min' => __('Phone number must be at least 10 digits'),
            'phone.regex' => __('Phone number format is invalid'),
        ];
    }
}
