<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportConfig extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('import_configs')->insert([
                [
                    'counter' => 1,
                    'is_water' => 0,
                    'device_type_id' => 1, // электричество
                    'device_place_id' => 1,  // квартирный
                    'is_active' => 1,
                ],
                [
                    'counter' => 2,
                    'is_water' => 0,
                    'device_type_id' => 1, // электричество
                    'device_place_id' => 2, // общедомовой
                        'is_active' => 0,
                ],
                [
                    'counter' => 3,
                    'is_water' => 0,
                    'device_type_id' => 2, // тепло
                    'device_place_id' => 1, // квартирный
                        'is_active' => 0,
                ],
                [
                    'counter' => 3,
                    'is_water' => 0,
                    'device_type_id' => 1, // тепло
                    'device_place_id' => 2,
                    'is_active' => 0,// общедомовой
                ],
                [
                    'counter' => 3,
                    'is_water' => 1,
                    'device_type_id' => 2, // вода
                    'device_place_id' => 1,
                    'is_active' => 0,// квартирный
                ],
                [
                    'counter' => 3,
                    'is_water' => 1,
                    'device_type_id' => 1, // вода
                    'device_place_id' => 2,
                    'is_active' => 0,// общедомовой
                ],
            ]
        );
    }
}
