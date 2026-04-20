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

        // Super Admin
        $admin = User::factory()->create([
            'first_name' => 'Test',
            'last_name'  => 'User',
            'email'      => 'test@example.com',
            'password'   => Hash::make('1'),
        ]);
        $admin->assignRole('super_admin');

        // Client
        $client = User::factory()->create([
            'first_name' => 'John',
            'last_name'  => 'Smith',
            'email'      => 'client@example.com',
            'password'   => Hash::make('1'),
        ]);
        $client->assignRole('client');

        // Client Manager
        $clientManager = User::factory()->create([
            'first_name' => 'Jane',
            'last_name'  => 'Doe',
            'email'      => 'clientmanager@example.com',
            'password'   => Hash::make('1'),
        ]);
        $clientManager->assignRole('client_manager');

        // Client Manager
        $reception = User::factory()->create([
            'first_name' => 'Tiam',
            'last_name'  => 'Senty',
            'email'      => 'reception@example.com',
            'password'   => Hash::make('1'),
        ]);
        $reception->assignRole('reception');

          // Client Manager
        $analyst = User::factory()->create([
            'first_name' => 'Willy',
            'last_name'  => 'Senty',
            'email'      => 'analyst@example.com',
            'password'   => Hash::make('1'),
        ]);
        $analyst->assignRole('analyst');

         // Client Manager
        $director = User::factory()->create([
            'first_name' => 'Willy',
            'last_name'  => 'Senty',
            'email'      => 'director@example.com',
            'password'   => Hash::make('1'),
        ]);
        $director->assignRole('director');

         // Client Manager
        $auditor = User::factory()->create([
            'first_name' => 'Kaseba',
            'last_name'  => 'Senty',
            'email'      => 'auditor@example.com',
            'password'   => Hash::make('1'),
        ]);
        $auditor->assignRole('auditor');
    }
}