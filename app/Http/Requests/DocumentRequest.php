<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
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
                    'title'      => 'required',
                    'document'   => 'required',
                    'image' => 'max:'.env('IMAGE_UPLOAD_SIZE').'|mimes:jpeg,jpg,png'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'title' => 'required',
                    'image' => 'max:'.env('IMAGE_UPLOAD_SIZE').'|mimes:jpeg,jpg,png'
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
            'title.required' => __('Title is required'),
            'document.required' => __('Document is required'),
            'image.mimes' => __('Image type must be a type of jpg,jpeg,png'),
            'image.max' => __('File is too Big, please select a File less than ').env('IMAGE_FILE_SIZE').__(' MB'),
        ];
    }
}
