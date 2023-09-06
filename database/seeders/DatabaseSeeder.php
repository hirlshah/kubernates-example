<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionTableSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(TagSeeder::class);
        //$this->call(EventSeeder::class);
        $this->call(SurveyQuestionMasterSeeder::class);
        $this->call(SurveyAnswerMasterSeeder::class);
        $this->call(SurveySeeder::class);
        $this->call(PlanSettingSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(StripeSeeder::class);
    }
}
