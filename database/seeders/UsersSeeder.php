<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Users\UserModule;
use App\Models\Users\UserPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin  =  \App\Models\Users\User::create([
            'name' => 'admin',
            'email' => config('app.email'),
            'password' => config('app.password'),
            'role_id' => 1
        ]);
        $modules = Module::all();
        $permissions = Permission::all();

        foreach ($modules as $m) {
            $userModule = new UserModule(
                [
                    'module_id' => $m->id
                ]
            );
            $admin->modules()->save($userModule);
        }

        foreach ($permissions as $p) {
            $userPermission = new UserPermission(
                [
                    'permission_id' => $p->id
                ]
            );
            $admin->permissions()->save($userPermission);
        }
    }
}
