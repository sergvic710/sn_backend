<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ExportType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('export_types')->insert([
            [
                'name' => 'Excel',
                'type' => 'xls',
            ],
            [
                'name' => 'Csv',
                'type' => 'csv',
            ],
            [
                'name' => 'Json',
                'type' => 'json',
            ],
        ]);
    }
}
