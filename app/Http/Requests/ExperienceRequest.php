<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceRequest extends FormRequest
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
            'title' => 'required',
            'company' => 'required',
            'start_date' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(){
        return [
            'title.required' => __('Title is required'),
            'company.required' => __('Company name is required'),
            'start_date.required' => __('Start date is required'),
            'image.required' => __('Photo is required')
        ];
    }
}
