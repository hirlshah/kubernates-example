<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Tag;
use Database\Factories\EventFactory;

class EventSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        Event::factory()
             ->count(20)
             ->create();

        foreach (Event::all() as $event){
            $tags = Tag::inRandomOrder()->limit(2)->pluck('id');
            $event->tags()->sync($tags);
        }
	}
}
