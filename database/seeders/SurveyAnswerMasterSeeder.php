<?php

namespace Database\Seeders;

use App\Models\SurveyAnswerMaster;
use Illuminate\Database\Seeder;

class SurveyAnswerMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(config('app.env') !== 'production'){
            $answers = [
                ['answer' => 'Yes'],
                ['answer' => 'No'],
                ['answer' => 'Maybe'],
                ['answer' => 'Not sure']
            ];

            foreach($answers as $a){
                SurveyAnswerMaster::create($a);
            }
        }

        $answers = [
            ['answer' => '1', 'type' => 'rating'],
            ['answer' => '2', 'type' => 'rating'],
            ['answer' => '3', 'type' => 'rating'],
            ['answer' => '4', 'type' => 'rating'],
            ['answer' => '5', 'type' => 'rating'],
        ];

        foreach($answers as $a){
            SurveyAnswerMaster::create($a);
        }

        $answers = [
            ['answer' => 'text', 'type' => 'text'],
        ];

        foreach($answers as $a){
            SurveyAnswerMaster::create($a);
        }
    }
}
