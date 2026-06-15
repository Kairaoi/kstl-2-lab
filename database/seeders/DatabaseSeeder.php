<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(AuditReportsSeeder::class);
        $this->call(AnalyticsReportsSeeder::class);

        $users = [
            ['first_name' => 'Test',   'last_name' => 'User',  'email' => 'test@example.com',        'role' => 'super_admin'],
            ['first_name' => 'John',   'last_name' => 'Smith', 'email' => 'client@example.com',       'role' => 'client'],
            ['first_name' => 'Jane',   'last_name' => 'Doe',   'email' => 'clientmanager@example.com','role' => 'client_manager'],
            ['first_name' => 'Tiam',   'last_name' => 'Senty', 'email' => 'reception@example.com',    'role' => 'reception'],
            ['first_name' => 'Willy',  'last_name' => 'Senty', 'email' => 'analyst@example.com',      'role' => 'analyst'],
            ['first_name' => 'Willy',  'last_name' => 'Senty', 'email' => 'director@example.com',     'role' => 'director'],
            ['first_name' => 'Kaseba', 'last_name' => 'Senty', 'email' => 'auditor@example.com',      'role' => 'auditor'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'first_name' => $data['first_name'],
                    'last_name'  => $data['last_name'],
                    'password'   => Hash::make('1'),
                ]
            );
            if (! $user->hasRole($data['role'])) {
                $user->assignRole($data['role']);
            }
        }

        // $this->call(DemoDataSeeder::class);
    }
}