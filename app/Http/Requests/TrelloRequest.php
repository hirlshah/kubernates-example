<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrelloRequest extends FormRequest
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
        switch($this->method())
        {
            case 'POST':
            {
                return [
                    'title' => 'min:1|max:191'
                ];
            }
            default:break;
        }
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(){
        return [
            'title.min' => __('The title must greater than 1 characters.'),
            'title.max' => __('The title must not be greater than 191 characters.'),
            
        ];
    }
}
