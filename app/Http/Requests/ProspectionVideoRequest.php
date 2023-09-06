<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProspectionVideoRequest extends FormRequest
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
                    'title'             => 'required|max:191',
                    'video'             => 'required|max:'.env('VIDEO_UPLOAD_SIZE').'|mimes:mp4',
                    'custom_title'      => 'required|max:191',
                    'video_cover_image' => 'max:1024|mimes:jpeg,jpg,png'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'title'             => 'required|max:191',
                    'video'             => 'max:'.env('VIDEO_UPLOAD_SIZE').'|mimes:mp4',
                    'custom_title'      => 'required|max:191',
                    'video_cover_image' => 'max:1024|mimes:jpeg,jpg,png'
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
            'title.required'            => __('Title is required'),
            'title.max'                 => __('The title must not be greater than 191 characters.'),
            'video.required'            => __('The video field is required'),
            'video.max'                 => __('File is too Big, please select a File less than ').env('VIDEO_FILE_SIZE').__(' MB'),
            'video.mimes'               => __('The video format must be .mp4. Please, check and try again.'),
            'custom_title.required'     => __('The custom title field is required.'),
            'custom_title.max'          => __('The custom title must not be greater than 191 characters.'),
            'video_cover_image.max'     => __('File is too big, please select an image smaller than 1 MB'),
            'video_cover_image.mimes'   => __('Video cover image type must be a type of jpg,jpeg,png'),
        ];
    }
}
