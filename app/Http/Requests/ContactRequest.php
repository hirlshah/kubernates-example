<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
        $now = getCarbonNowForUser();
        $todayDate = $now->format('d/m/Y');
        return [
            'name' => 'required|max:191',
            'email' => 'nullable|email',
            'contact_image' => 'max:'.env('IMAGE_UPLOAD_SIZE').'|mimes:jpg,jpeg,png',
            'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'follow_up_date' => 'nullable|date_format:d/m/Y',
            'link' => 'max:191',
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
            'name.required' => __('Full Name is required'),
            'name.max' => __('Full name not be greater than 191 characters.'),
            'email.email' => __('Enter valid email'),
            'contact_image.mimes' => __('Image type must be a type of jpg,jpeg,png'),
            'contact_image.max' => __('File is too Big, please select a File less than ').env('IMAGE_FILE_SIZE').__(' MB'),
            'phone.min' => __('Phone number must be at least 10 digits'),
            'phone.regex' => __('Phone number format is invalid'),
            'link.max' => __('The link must not be greater than 191 characters.'),
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if(isset($this->follow_up_date) && !empty($this->follow_up_date)) {
                $userCurrentDate = convertDateFormatWithTimezone(getCarbonNowForUser(), 'Y-m-d H:i:s', 'Y-m-d H:i:s');
                $followUpDate = convertDateFormatWithTimezone($this->follow_up_date . ' 00:00:00', 'd/m/Y H:i:s', 'Y-m-d H:i:s');
                
                if(strtotime($followUpDate) <= strtotime($userCurrentDate)) {
                    $validator->errors()->add('follow_up_date', __('The follow up date must be a date after today.'));
                }
            }
        });
    }
}
