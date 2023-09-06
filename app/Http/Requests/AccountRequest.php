<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'profile_image' => 'max:'.env('IMAGE_UPLOAD_SIZE').'|mimes:jpg',
            'phone' => 'nullable|digits:10',
            'permanent_zoom_link' => 'nullable|url',
            'age' => 'numeric',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(){
        return [
            'name.required' => __('First Name is required'),
            'last_name.required' => __('Last Name is required'),
            'email.required' => __('Email is required'),
            'profile_image.mimes' => __('Image must be a file of type:jpg'),
            'profile_image.max' => __('File is too Big, please select a File less than ').env('IMAGE_FILE_SIZE').__(' MB'),
            'phone.digits' => __('Phone number must be at least 10 digits'),
            'permanent_zoom_link.url' => __('Please enter proper URL.'),
            'age.numeric' => __('The age must be a number.'),
        ];
    }
}
