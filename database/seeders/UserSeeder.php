<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'example@example.com',
            'password' => Hash::make('password')
        ]);
    }
}
