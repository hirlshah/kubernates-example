<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class HelpRequest extends FormRequest
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
    public function rules(Request $request)
    {
        switch($this->method())
        {
            case 'POST':
            {
                return [
                    'title_en' => 'required',
                    'title_fr' => 'required',
                    'url'     => 'required'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'title_en' => 'required',
                    'title_fr' => 'required',
                    'url'     => 'required'
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
            'title_en.required' => __('Title english is required'),
            'title_fr.required' => __('Title french is required'),
            'url.required' => __('The url is required'),
        ];
    }
}
