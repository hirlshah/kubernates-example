<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactSendMessageRequest extends FormRequest
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
        $rules = [
            'message' => 'required',
            'id' => 'required',
        ];
        return $rules;
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(){
        return [
            'message.required' => __('Message is required'),
            'id.required' => __('Contact missing')
        ];
    }
}
