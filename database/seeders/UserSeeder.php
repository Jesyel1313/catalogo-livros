<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * User seed.
 */
class UserSeeder extends Seeder
{
    /**
     * Seed the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.test',
            'password' => Hash::make('12345678')
        ]);
    }
}
