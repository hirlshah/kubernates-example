<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Survey extends Authenticatable 
{
	use HasApiTokens, HasFactory, Notifiable;

	protected $table = 'surveys';

	protected $guarded = ['id'];

    /**
     * Get options
     */
    public static function getOptions() {
        return self::query()->orderBy('id','asc')->pluck('name', 'id')->take(1);
    }

    /**
     * Survey questions
     */
    public function surveyQuestions() {
        return $this->belongsToMany('App\Models\SurveyQuestionMaster', 'survey_questions', 'survey_id', 'question_id')->withPivot('with_comment','answers_ids');
    }

    /**
     * Get answers
     */
    public function getAnswers($answerIds) {
        if(empty($answerIds)) return [];
        return SurveyAnswerMaster::whereIn('id', explode(",", $answerIds))->get();
    }
}
