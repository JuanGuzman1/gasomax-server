<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'COMPRAS',
            'DH',
            'MTTO',
            'MKT',
            'NORMATIVIDAD',
            'NOMINA',
            'GESTORIA',
            'SISTEMAS',
            'OPERACION',
            'DIRECCION',
            'CARWASH',
            'RESTAURANTE',
            'MAXSTORE'
        ];
        foreach ($departments as $d) {
            $departmentExists = \App\Models\Users\Department::where('name', $d)
                ->exists();

            if (!$departmentExists) {
                \App\Models\Users\Department::create([
                    'name' => $d,
                ]);
            }
        }
    }
}
