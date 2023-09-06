<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurveyRequest extends FormRequest
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
            'survey_questions' => 'array|min:1',
            'survey_questions.*.questions' => 'required',
            'survey_questions.*.with_comment' => 'integer',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(){
        return [
            'survey_questions.array' => __('An error occurred while adding this poll.'),
            'survey_questions.min' => __('An error occurred while adding this poll.'),
            'survey_questions.*.questions.required' => __('Question is required')
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $survey_questions = $this->survey_questions;
        unset($survey_questions['##']);
        $this->merge(['survey_questions' => $survey_questions]);
    }
}
