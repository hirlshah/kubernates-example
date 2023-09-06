<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LabelRequest extends FormRequest
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
			'color' => 'required',
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
			'color.required' => __('color is required')
		];
	}
}
