<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class FollowUpRequest extends FormRequest
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
            'follow_up_date' => 'required',
            'reason' => 'required|string',
            'contact_id' => 'required|integer'
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(){
        return [
            'reason.required' => __('Reason is Required'),
            'follow_up_date.required' => __('Date is required')
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if(isset($this->follow_up_date) && !empty($this->follow_up_date)) {
                $userCurrentDate = convertDateFormatWithTimezone(getCarbonNowForUser(), 'Y-m-d H:i:s', 'Y-m-d');
                $followUpDate = convertDateFormatWithTimezone($this->follow_up_date . ' 00:00:00', 'd/m/Y H:i:s', 'Y-m-d');

                if(strtotime($followUpDate) <= strtotime($userCurrentDate)) {
                    $validator->errors()->add('follow_up_date', __('The follow up date must be a date after today.'));
                }
            }
        });
    }
}
