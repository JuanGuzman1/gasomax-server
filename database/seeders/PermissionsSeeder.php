<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Administration
        //Banks permissions
        \App\Models\Permission::create([
            'name' => 'create',
            'module_id' => 1,
        ]);
        \App\Models\Permission::create([
            'name' => 'index',
            'module_id' => 1,
        ]);
        \App\Models\Permission::create([
            'name' => 'edit',
            'module_id' => 1,
        ]);
        \App\Models\Permission::create([
            'name' => 'delete',
            'module_id' => 1,
        ]);

        //Providers permissions
        \App\Models\Permission::create([
            'name' => 'create',
            'module_id' => 2,
        ]);
        \App\Models\Permission::create([
            'name' => 'index',
            'module_id' => 2,
        ]);
        \App\Models\Permission::create([
            'name' => 'edit',
            'module_id' => 2,
        ]);
        \App\Models\Permission::create([
            'name' => 'delete',
            'module_id' => 2,
        ]);

        //Payments
        //Quotes permissions
        \App\Models\Permission::create([
            'name' => 'create',
            'module_id' => 3,
        ]);
        \App\Models\Permission::create([
            'name' => 'index',
            'module_id' => 3,
        ]);
        \App\Models\Permission::create([
            'name' => 'show',
            'module_id' => 3,
        ]);
        \App\Models\Permission::create([
            'name' => 'edit',
            'module_id' => 3,
        ]);
        \App\Models\Permission::create([
            'name' => 'delete',
            'module_id' => 3,
        ]);
        \App\Models\Permission::create([
            'name' => 'reject',
            'module_id' => 3,
        ]);
        \App\Models\Permission::create([
            'name' => 'pay',
            'module_id' => 3,
        ]);
        \App\Models\Permission::create([
            'name' => 'authorize',
            'module_id' => 3,
        ]);
        \App\Models\Permission::create([
            'name' => 'authorize.ok',
            'module_id' => 3,
        ]);
        \App\Models\Permission::create([
            'name' => 'upload.quote',
            'module_id' => 3,
        ]);

        //PurchaseRequest permissions
        \App\Models\Permission::create([
            'name' => 'create',
            'module_id' => 4,
        ]);
        \App\Models\Permission::create([
            'name' => 'index',
            'module_id' => 4,
        ]);
        \App\Models\Permission::create([
            'name' => 'show',
            'module_id' => 4,
        ]);
        \App\Models\Permission::create([
            'name' => 'edit',
            'module_id' => 4,
        ]);
        \App\Models\Permission::create([
            'name' => 'delete',
            'module_id' => 4,
        ]);
        \App\Models\Permission::create([
            'name' => 'reject',
            'module_id' => 4,
        ]);
        \App\Models\Permission::create([
            'name' => 'pay',
            'module_id' => 4,
        ]);
        \App\Models\Permission::create([
            'name' => 'authorize',
            'module_id' => 4,
        ]);

        //PendingPayments (only view module)
        \App\Models\Permission::create([
            'name' => 'create',
            'module_id' => 5,
        ]);
        \App\Models\Permission::create([
            'name' => 'index',
            'module_id' => 5,
        ]);
        \App\Models\Permission::create([
            'name' => 'edit',
            'module_id' => 5,
        ]);
        \App\Models\Permission::create([
            'name' => 'delete',
            'module_id' => 5,
        ]);

        //users
        //departments permissions
        \App\Models\Permission::create([
            'name' => 'create',
            'module_id' => 6,
        ]);
        \App\Models\Permission::create([
            'name' => 'index',
            'module_id' => 6,
        ]);
        \App\Models\Permission::create([
            'name' => 'edit',
            'module_id' => 6,
        ]);
        \App\Models\Permission::create([
            'name' => 'delete',
            'module_id' => 6,
        ]);

        //users permissions
        \App\Models\Permission::create([
            'name' => 'create',
            'module_id' => 7,
        ]);
        \App\Models\Permission::create([
            'name' => 'index',
            'module_id' => 7,
        ]);
        \App\Models\Permission::create([
            'name' => 'edit',
            'module_id' => 7,
        ]);
        \App\Models\Permission::create([
            'name' => 'delete',
            'module_id' => 7,
        ]);
        \App\Models\Permission::create([
            'name' => 'modules',
            'module_id' => 7,
        ]);
        \App\Models\Permission::create([
            'name' => 'permissions',
            'module_id' => 7,
        ]);
    }
}
