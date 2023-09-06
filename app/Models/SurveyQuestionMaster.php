<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestionMaster extends Model
{
    use HasFactory;

    protected $table = 'survey_question_master';

    protected $guarded = ['id'];

    /**
     * Get options
     */
    public static function getOptions() {
        return self::query()->orderBy('id','asc')->pluck('title', 'id');
    }

    /**
     * Get rating question ids
     */
    public static function getRatingQuestionIds() {
        return self::query()->where('is_rating',1)->pluck('id')->toArray();
    }

    /**
     * Surveys
     */
    public function surveys() {
        return $this->belongsToMany('App\Models\Survey', 'survey_questions', 'survey_id', 'question_id');
    }
}
