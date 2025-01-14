<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    static private $users;

    public function __construct()
    {
        self::$users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => 'Password123',
                'role' => 'admin'
            ],
            [
                'name' => 'Consumer',
                'email' => 'consumer@gmail.com',
                'password' => 'Password123',
                'role' => 'user'
            ]
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::$users as $user) {
            User::create($user);
        }
    }
}
