<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class ModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'module' => 'administration',
                'submodule' => 'banks'
            ],
            [
                'module' => 'administration',
                'submodule' => 'providers'
            ],
            [
                'module' => 'payments',
                'submodule' => 'purchaseRequest'
            ],
            [
                'module' => 'payments',
                'submodule' => 'pendingPayments'
            ],
            [
                'module' => 'users',
                'submodule' => 'departments'
            ],
            [
                'module' => 'users',
                'submodule' => 'users'
            ],
        ];


        foreach ($modules as $m) {
            $moduleExists = \App\Models\Module::where('module', $m['module'])
                ->where('submodule', $m['submodule'])->exists();

            if (!$moduleExists) {
                \App\Models\Module::create([
                    'module' => $m['module'],
                    'submodule' => $m['submodule'],
                ]);
            }
        }
    }
}
