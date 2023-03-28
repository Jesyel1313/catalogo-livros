<?php

namespace Database\Seeders;

use App\Models\Category;

use Illuminate\Database\Seeder;

/**
 * Category seed.
 */
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory(100)->create();
    }
}
