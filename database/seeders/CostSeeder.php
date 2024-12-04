<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('costs')->insert([
            ['name' => 'Безкоштовно'],
            ['name' => 'Ряд 1'],
            ['name' => 'Ряд 2'],
            ['name' => 'Ряд 3'],
            ['name' => 'Ряд 4'],
        ]);
    }
}
