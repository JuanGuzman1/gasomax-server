<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            //autorizan
            'ADMIN',
            'DIRECCION GENERAL',
            'SUBDIRECCION GENERAL',
            'COORDINADOR',
            'GERENTE',
            //solicitan
            'JEFE',
            'EJECUTIVO',
            'AUXILIAR'
        ];
        foreach ($roles as $r) {
            $roleExist = \App\Models\Users\Role::where('name', $r)
                ->exists();

            if (!$roleExist) {
                \App\Models\Users\Role::create([
                    'name' => $r,
                ]);
            }
        }
    }
}
