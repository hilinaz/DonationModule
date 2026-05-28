<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        // Seed one user for each role
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@admin',
                'password' => 'admin123',
                'role' => 'Admin',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'fundraising@donateflow.com',
                'password' => 'password',
                'role' => 'Fundraising Manager',
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'finance@donateflow.com',
                'password' => 'password',
                'role' => 'Finance',
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'marketing@donateflow.com',
                'password' => 'password',
                'role' => 'Marketing',
            ],
            [
                'name' => 'James Wilson',
                'email' => 'donor@donateflow.com',
                'password' => 'password',
                'role' => 'Donor',
            ],
            [
                'name' => 'Patricia Brown',
                'email' => 'auditor@donateflow.com',
                'password' => 'password',
                'role' => 'Auditor',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'email_verified_at' => now(),
                ]
            );

            $user->assignRole($userData['role']);
        }
    }
}
