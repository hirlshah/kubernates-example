<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
                    'name'              => 'required',
                    'roles'             => 'required',
                    'date_of_birth'     => 'required',
                    'description'       => 'required',
                    'email'             => 'required|email|unique:users,email',
                    'user_name'         => 'required|unique:users,user_name',
                    'password'          => 'required',
                    'confirm_password'  => 'required|same:password',
                    'video'             => 'mimes:mp4',
                    'profile_image'     => 'mimes:jpg,jpeg'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name'          => 'required',
                    'date_of_birth' => 'required',
                    'email'         =>  ['required','email', Rule::unique('users', 'email')->ignore($this->user)],
                    'user_name'     =>  ['required', Rule::unique('users', 'user_name')->ignore($this->user)],
                    'video'         => 'mimes:mp4',
                    'profile_image' => 'max:'.env('IMAGE_UPLOAD_SIZE').'mimes:jpg,jpeg'
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
            'name.required'             => __('Name is required'),
            'roles.required'            => __('Role is required'),
            'date_of_birth.required'    => __('Date of birth is required'),
            'description.required'      => __('Description is required'),
            'email.required'            => __('Email is required'),
            'user_name.required'        => __('User Name is required'),
            'password.required'          => __('Password is required'),
            'confirm_password.required' => __('Confirm password is required'),
            'confirm_password.same'     => __("The confirm password and password must match."),
            'email.unique'              => __("Email has already been taken"),
            'email.email'               => __('Enter valid email'),
            'user_name.unique'          => __('User name has already been taken'),
            'profile_image.max'         => __('File is too Big, please select a File less than ').env('IMAGE_FILE_SIZE').__(' MB'),
        ];
    }
}
