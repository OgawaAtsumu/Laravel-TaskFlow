<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::firstOrCreate(['name' => '仕事']);
        Category::firstOrCreate(['name' => '学習']);
        Category::firstOrCreate(['name' => 'プライベート']);
        Category::firstOrCreate(['name' => 'その他']);
    }
}