<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Example',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        Category::create([
            'name' => 'Web Basic',
            'description' => 'learn about web basic',
        ]);
    }
}
