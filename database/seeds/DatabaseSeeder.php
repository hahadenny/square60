<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(FiltersTableSeeder::class);
        $this->call(FiltersDataTableSeeder::class);
        $this->call(RoleTableSeeder::class);
    }
}
