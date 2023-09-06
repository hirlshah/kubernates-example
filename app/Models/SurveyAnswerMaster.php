<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswerMaster extends Model
{
    use HasFactory;

    protected $table = 'survey_answer_master';

    protected $guarded = ['id'];

    /**
     * Get options
     */
    public static function getOptions() {
        return self::query()->where('type','option')->orderBy('id','asc')->pluck('answer', 'id');
    }

    /**
     * Get text answer ids
     */
    public static function getTextAnswerId() {
        $answerId = self::query()->where('type','text')->first();
        if(!empty($answerId)) {
            return $answerId->id;
        }
        return null;
    }
}
