<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactStatusUpdateRequest extends FormRequest
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
            'current_status' => 'integer|required',
            'update_status' => 'integer|required',
            'board_id' => 'integer|required',
            'id' => 'integer|required',
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

        ];
    }
}
