<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('device_types')->insert([
            'id' => 1,
            'name' => 'Электричество',
        ]);
        DB::table('device_types')->insert([
            'id' => 2,
            'name' => 'Тепло',
        ]);
        DB::table('device_types')->insert([
            'id' => 3,
            'name' => 'Вода',
        ]);
    }
}
