<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Музика'],
            ['name' => 'Кіно'],
            ['name' => 'Театр'],
            ['name' => 'Мистецтво'],
            ['name' => 'Освіта'],
            ['name' => 'Tech&IT'],
            ['name' => 'Спорт'],
            ['name' => 'Соціальні події'],
            ['name' => 'Інше'],
        ]);
    }
}
