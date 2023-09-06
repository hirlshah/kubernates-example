<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserEventSurveyRequest extends FormRequest
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
            'event_id' => 'required',
            'survey_id' => 'required',
            'answer_ids' => 'array|min:1',
            'answer_ids.*.question' => 'required|integer',
            'answer_ids.*.answer' => 'required_unless:answer_ids.*.only_comment, 1|string',
            'answer_ids.*.comment' => 'nullable|string',
            'answer_ids.*.answers_text' => 'required',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(){
        return [
            'answer_ids.min' => __('Please answer all questions'),
            'answer_ids.*.question.required' => __('Please answer all questions'),
            'answer_ids.*.answer.required' => __('Please select at least one answer'),
            'answer_ids.*.answer.required_unless' => __('Please select at least one answer'),
            'answer_ids.*.answers_text.required' => __('Please enter answer'),
        ];
    }
}
