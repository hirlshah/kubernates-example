<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'meeting_date' => 'required',
            'meeting_time' => 'required',
            'meeting_url' => 'required|url|max:191',
            'image' => 'max:'.env('IMAGE_UPLOAD_SIZE').'|mimes:jpeg,jpg,png'
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(){
        return [
            'name.required' => __('Name is required'),
            'meeting_date.required' => __('Meeting Date is required'),
            'meeting_time.required' => __('Meeting Time is required'),
            'meeting_url.required' => __('Meeting Url is required'),
            'meeting_url.max'      => __('The meeting url must not be greater than 191 characters.'),
            'image.mimes' => __('Image type must be a type of jpg,jpeg,png'),
            'image.max' => __('File is too Big, please select a File less than ').env('IMAGE_FILE_SIZE').__(' MB'),
        ];
    }
}
