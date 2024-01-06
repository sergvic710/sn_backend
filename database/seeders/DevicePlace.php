<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DevicePlace extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('device_places')->insert([
            'id' => 1,
            'name' => 'Квартирные',
        ]);
        DB::table('device_places')->insert([
            'id' => 2,
            'name' => 'Общедомовые',
        ]);
    }
}
