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
        $this->call([
            RolesSeeder::class,
            DepartmentsSeeder::class,
            ModulesSeeder::class,
            PermissionsSeeder::class,
            UsersSeeder::class,
            QuoteConceptsExcelSeeder::class,
            UnitsExcelSeeder::class
        ]);
    }
}
