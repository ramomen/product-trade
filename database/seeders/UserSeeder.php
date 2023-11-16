<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Ramazan Degirmenci',
                'email' => 'ramazan@test.com',
                'password' => Hash::make('123456')
            ],
            [
                'name' => 'Verde',
                'email' => 'verde@laravel.com',
                'password' => Hash::make('123456')
            ],
        ];

        foreach ($users as $user) {
            User::insert($user);
        }
    }
}
