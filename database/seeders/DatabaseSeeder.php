<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Users\User::create([
            'name' => 'admin',
            'email' => config('app.email'),
            'password' => config('app.password'),
            'role' => 'superadmin'
        ]);
        $this->call(ModulesSeeder::class);
        $this->call(PermissionsSeeder::class);
    }
}
