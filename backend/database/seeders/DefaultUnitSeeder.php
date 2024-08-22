<?php

namespace Database\Seeders;

use App\Models\DefaultUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DefaultUnit::truncate();
        DefaultUnit::insert([
            ['id' => 1, 'name' => 'count'],
            ['id' => 2, 'name' => 'min'],
            ['id' => 3, 'name' => 'hr'],
            ['id' => 4, 'name' => 'ml'],
            ['id' => 5, 'name' => 'cups'],
            ['id' => 6, 'name' => 'Cal'],
            ['id' => 7, 'name' => 'steps'],
            ['id' => 8, 'name' => 'km'],
        ]);
    }
}
