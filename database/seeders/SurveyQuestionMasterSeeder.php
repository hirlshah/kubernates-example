<?php

namespace Database\Seeders;

use App\Models\SurveyQuestionMaster;
use Illuminate\Database\Seeder;

class SurveyQuestionMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(config('app.env') !== 'production'){
            $questions = [
                ['title' => 'Qu’est-ce que tu as le plus aimé de l’opportunité?'],
                ['title' => 'Qu’est-ce que tu as le plus aimé de nos produits?'],
            ];

            foreach($questions as $q){
                SurveyQuestionMaster::create($q);
            }

            // SurveyQuestionMaster::create([
            //     'title' => 'Please give this event rating.',
            //     'is_rating' => 1
            // ]);
        }
    }
}
