<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;



class FiltersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('filters')->truncate();
            DB::table('filters')->insert(
                [
                    [
                        'name' => 'district',
                        'label' => '',
                        'type' => 'linksline'
                    ],
                    [
                        'name' => 'sub_districts',
                        'label' => 'SELECT LOCATION',
                        'type' => 'checkbox'
                    ],
                    [
                        'name' => 'type',
                        'label' => 'TYPE',
                        'type' => 'checkbox'
                    ],
                    [
                        'name' => 'bed',
                        'label' => 'Bed:',
                        'type' => 'select'
                    ],
                    [
                        'name' => 'bath',
                        'label' => 'Bath:',
                        'type' => 'select'
                    ],
                    [
                        'name' => 'price',
                        'label' => 'Price:',
                        'type' => 'input'
                    ],
                    [
                        'name' => 'filters',
                        'label' => 'Filters:',
                        'type' => 'checkbox'
                    ],
                ]
            );
    }
}
