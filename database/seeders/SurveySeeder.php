<?php

namespace Database\Seeders;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(config('app.env') !== 'production'){
            $surveys = [
                ['name' => 'Default Survey'],
            ];

            $questions = [1,2,3,4];

            foreach($surveys as $s){
                $survey = Survey::create($s);
                foreach ($questions as $q){
                    SurveyQuestion::create([
                        'survey_id' => $survey->id,
                        'question_id' => $q,
                        'answers_ids'=> '10',
                        'with_comment' => 1
                    ]);
                }
                SurveyQuestion::create([
                    'survey_id' => $survey->id,
                    'question_id' => 5,
                    'with_comment' => 1
                ]);
                SurveyQuestion::create([
                    'survey_id' => $survey->id,
                    'question_id' => 6,
                    'answers_ids'=> '5,6,7,8,9',
                    'with_comment' => 0
                ]);
            }

        }
    }
}
