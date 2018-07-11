<?php

use Illuminate\Database\Seeder;

class OpenHoursTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('open_hours')->truncate();
        DB::table('open_hours')->insert(
            [
                [
                    'hour' => '8:00',
                    'title' => '8:00 AM'
                ],
                [
                    'hour' => '8:30',
                    'title' => '8:30 AM'
                ],
                [
                    'hour' => '9:00',
                    'title' => '9:00 AM'
                ],
                [
                    'hour' => '9:30',
                    'title' => '9:30 AM'
                ],
                [
                    'hour' => '10:00',
                    'title' => '10:00 AM'
                ],
                [
                    'hour' => '10:30',
                    'title' => '10:30 AM'
                ],
                [
                    'hour' => '11:00',
                    'title' => '11:00 AM'
                ],
                [
                    'hour' => '11:30',
                    'title' => '11:30 AM'
                ],
                [
                    'hour' => '12:00',
                    'title' => '12:00 AM'
                ],
                [
                    'hour' => '12:30',
                    'title' => '12:30 AM'
                ],
                [
                    'hour' => '13:00',
                    'title' => '1:00 PM'
                ],
                [
                    'hour' => '13:30',
                    'title' => '1:30 PM'
                ],
                [
                    'hour' => '14:00',
                    'title' => '2:00 PM'
                ],
                [
                    'hour' => '14:30',
                    'title' => '2:30 PM'
                ],
                [
                    'hour' => '15:00',
                    'title' => '3:00 PM'
                ],
                [
                    'hour' => '15:30',
                    'title' => '3:30 PM'
                ],
                [
                    'hour' => '16:00',
                    'title' => '4:00 PM'
                ],
                [
                    'hour' => '16:30',
                    'title' => '4:30 PM'
                ],
                [
                    'hour' => '17:00',
                    'title' => '5:00 PM'
                ],
            ]
        );
    }
}
