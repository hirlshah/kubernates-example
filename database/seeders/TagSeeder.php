<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::insert([
            ['name' => 'Social Media'],
            ['name' => 'Networking'],
            ['name' => 'Marketing'],
            ['name' => 'Design'],
            ['name' => 'Finance'],
            ['name' => 'Podcasts'],
        ]);
    }
}
