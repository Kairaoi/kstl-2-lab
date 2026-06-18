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
            ['first_name' => 'Bineta',     'last_name' => 'Ruaia',    'email' => 'kstladmin@mfor.gov.ki',         'role' => 'super_admin'],
            ['first_name' => 'John',     'last_name' => 'Smith',   'email' => 'client@example.com',        'role' => 'client'],
            ['first_name' => 'Jane',     'last_name' => 'Doe',     'email' => 'clientmanager@example.com', 'role' => 'client_manager'],
            ['first_name' => 'Felomina', 'last_name' => 'Rontale', 'email' => 'felominar@mfor.gov.ki',     'role' => 'reception'],
            ['first_name' => 'Lizzie',   'last_name' => 'Maruia',  'email' => 'lizziem@mfor.gov.ki',        'role' => 'analyst'],
            ['first_name' => 'Enita',    'last_name' => 'Enoka',   'email' => 'enitae@mfor.gov.ki',        'role' => 'analyst'],
            ['first_name' => 'Bineta',   'last_name' => 'Ruaia',   'email' => 'binetar@mfor.gov.ki',       'role' => 'director'],
            ['first_name' => 'Kaseba',   'last_name' => 'Senty',   'email' => 'auditor@example.com',       'role' => 'auditor'],
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