<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Timezone;

class TimezoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timezoneIdentifiers = \DateTimeZone::listIdentifiers();

        foreach ($timezoneIdentifiers as $timezoneIdentifier) {
            $timezone = new \DateTimeZone($timezoneIdentifier);
            $offsetSeconds = $timezone->getOffset(new \DateTime());
            $offsetHours = abs($offsetSeconds) / 3600;
            $offsetSign = ($offsetSeconds >= 0) ? '+' : '-';
            $offsetFormatted = sprintf('%s%02d:%02d', $offsetSign, floor($offsetHours), ($offsetHours * 60) % 60);
            $description = $timezoneIdentifier .' (UTC ' . $offsetFormatted . ')';
            Timezone::updateOrCreate([
                'code' => $timezoneIdentifier
            ],
            [
                'code' => $timezoneIdentifier,
                'name' => $description
            ]);
        }
    }
}
