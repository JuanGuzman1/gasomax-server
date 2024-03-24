<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Users\Department;
use App\Models\Users\Role;
use App\Models\Users\User;
use App\Models\Users\UserModule;
use App\Models\Users\UserPermission;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponseTrait;

    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $name = $request->name;
        $role = $request->role;

        return $this->user->with([
            'department', 'modules',
            'permissions', 'role'
        ])->when($name, function ($query) use ($name) {
            return $query->where('name',  'like', '%' . $name . '%');
        })->when($role, function ($query) use ($role) {
            $query->whereHas('role', function ($query) use ($role) {
                return $query->where('name', 'like', '%' . $role . '%');
            });
        })->orderBy('created_at', 'asc')->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = $this->user->create($request->all());

            if ($request->department_id && $request->role_id) {

                $department = Department::find($request->department_id);
                $role = Role::find($request->role_id);
                $modules = Module::all();
                //GERENTES
                if ($department->name !== 'DIRECCION' && $role->name === 'GERENTE') {
                    foreach ($modules as $m) {
                        if ($m->submodule === 'banks') {
                            $userModule = new UserModule(['module_id' => $m->id]);
                            $user->modules()->save($userModule);
                            $permissions = Permission::where('module_id', $m->id)
                                ->whereIn('name', array('index'))->get();
                            foreach ($permissions as $p) {
                                $userPermission = new UserPermission(['permission_id' => $p->id]);
                                $user->permissions()->save($userPermission);
                            }
                        }
                        if ($m->submodule === 'providers') {
                            $userModule = new UserModule(['module_id' => $m->id]);
                            $user->modules()->save($userModule);
                            $permissions = Permission::where('module_id', $m->id)
                                ->whereIn('name', array('index'))->get();
                            foreach ($permissions as $p) {
                                $userPermission = new UserPermission(['permission_id' => $p->id]);
                                $user->permissions()->save($userPermission);
                            }
                        }
                        if ($m->submodule === 'quotes') {
                            $userModule = new UserModule(['module_id' => $m->id]);
                            $user->modules()->save($userModule);
                            $permissions = Permission::where('module_id', $m->id)
                                ->whereIn('name', array(
                                    'create', 'index', 'edit', 'show', 'delete', 'reject',
                                    'approve', 'authorize.minor.1000', 'authorize.minor.5000',
                                ))->get();
                            foreach ($permissions as $p) {
                                $userPermission = new UserPermission(['permission_id' => $p->id]);
                                $user->permissions()->save($userPermission);
                            }
                        }
                        if ($m->submodule === 'purchaseRequest') {
                            $userModule = new UserModule(['module_id' => $m->id]);
                            $user->modules()->save($userModule);
                            $permissions = Permission::where('module_id', $m->id)
                                ->whereIn('name', array(
                                    'create', 'index', 'edit', 'show', 'delete', 'reject',
                                    'authorize'
                                ))->get();
                            foreach ($permissions as $p) {
                                $userPermission = new UserPermission(['permission_id' => $p->id]);
                                $user->permissions()->save($userPermission);
                            }
                        }
                        if ($m->submodule === 'pendingPayments') {
                            $userModule = new UserModule(['module_id' => $m->id]);
                            $user->modules()->save($userModule);
                            $permissions = Permission::where('module_id', $m->id)
                                ->whereIn('name', array(
                                    'create', 'index', 'edit', 'delete'
                                ))->get();
                            foreach ($permissions as $p) {
                                $userPermission = new UserPermission(['permission_id' => $p->id]);
                                $user->permissions()->save($userPermission);
                            }
                        }
                    }
                }
                if ($department->name === 'DIRECCION') {
                    foreach ($modules as $m) {
                        if ($m->submodule === 'banks') {
                            $userModule = new UserModule(['module_id' => $m->id]);
                            $user->modules()->save($userModule);
                            $permissions = Permission::where('module_id', $m->id)
                                ->whereIn('name', array('index'))->get();
                            foreach ($permissions as $p) {
                                $userPermission = new UserPermission(['permission_id' => $p->id]);
                                $user->permissions()->save($userPermission);
                            }
                        }
                        if ($m->submodule === 'providers') {
                            $userModule = new UserModule(['module_id' => $m->id]);
                            $user->modules()->save($userModule);
                            $permissions = Permission::where('module_id', $m->id)
                                ->whereIn('name', array('index'))->get();
                            foreach ($permissions as $p) {
                                $userPermission = new UserPermission(['permission_id' => $p->id]);
                                $user->permissions()->save($userPermission);
                            }
                        }
                        if ($m->submodule === 'quotes') {
                            $userModule = new UserModule(['module_id' => $m->id]);
                            $user->modules()->save($userModule);
                            $permissions = Permission::where('module_id', $m->id)
                                ->whereIn('name', array(
                                    'create', 'index', 'edit', 'show', 'delete', 'reject',
                                    'approve', 'authorize.mayor.1000', 'authorize.mayor.5000',
                                ))->get();
                            foreach ($permissions as $p) {
                                $userPermission = new UserPermission(['permission_id' => $p->id]);
                                $user->permissions()->save($userPermission);
                            }
                        }
                        if ($m->submodule === 'purchaseRequest') {
                            $userModule = new UserModule(['module_id' => $m->id]);
                            $user->modules()->save($userModule);
                            $permissions = Permission::where('module_id', $m->id)
                                ->whereIn('name', array(
                                    'create', 'index', 'edit', 'show', 'delete', 'reject',
                                    'authorize'
                                ))->get();
                            foreach ($permissions as $p) {
                                $userPermission = new UserPermission(['permission_id' => $p->id]);
                                $user->permissions()->save($userPermission);
                            }
                        }
                        if ($m->submodule === 'pendingPayments') {
                            $userModule = new UserModule(['module_id' => $m->id]);
                            $user->modules()->save($userModule);
                            $permissions = Permission::where('module_id', $m->id)
                                ->whereIn('name', array(
                                    'create', 'index', 'edit', 'delete'
                                ))->get();
                            foreach ($permissions as $p) {
                                $userPermission = new UserPermission(['permission_id' => $p->id]);
                                $user->permissions()->save($userPermission);
                            }
                        }
                    }
                }
                if ($department->name !== 'DIRECCION' && $role->name !== 'GERENTE') {
                    foreach ($modules as $m) {

                        if ($m->submodule === 'quotes') {
                            $userModule = new UserModule(['module_id' => $m->id]);
                            $user->modules()->save($userModule);
                            $permissions = Permission::where('module_id', $m->id)
                                ->whereIn('name', array(
                                    'create', 'index', 'edit', 'show', 'delete', 'reject',
                                    'approve',
                                ))->get();
                            foreach ($permissions as $p) {
                                $userPermission = new UserPermission(['permission_id' => $p->id]);
                                $user->permissions()->save($userPermission);
                            }
                        }
                        if ($m->submodule === 'purchaseRequest') {
                            $userModule = new UserModule(['module_id' => $m->id]);
                            $user->modules()->save($userModule);
                            $permissions = Permission::where('module_id', $m->id)
                                ->whereIn('name', array(
                                    'create', 'index', 'edit', 'show', 'delete',

                                ))->get();
                            foreach ($permissions as $p) {
                                $userPermission = new UserPermission(['permission_id' => $p->id]);
                                $user->permissions()->save($userPermission);
                            }
                        }
                        if ($m->submodule === 'pendingPayments') {
                            $userModule = new UserModule(['module_id' => $m->id]);
                            $user->modules()->save($userModule);
                            $permissions = Permission::where('module_id', $m->id)
                                ->whereIn('name', array(
                                    'create', 'index', 'edit', 'delete'
                                ))->get();
                            foreach ($permissions as $p) {
                                $userPermission = new UserPermission(['permission_id' => $p->id]);
                                $user->permissions()->save($userPermission);
                            }
                        }
                    }
                }
            }

            $userRes = $this->user->with(['department', 'modules', 'permissions', 'role'])
                ->where('id', $user->id)->firstOrFail();
            return $this->successResponse($userRes);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->user->find($id);
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = $this->user->find($id);
            $user->update($request->all());
            $userRes = $this->user->with(['department', 'modules', 'permissions', 'role'])
                ->where('id', $id)->firstOrFail();
            return $this->successResponse($userRes);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = $this->user->find($id);
            $data = $user->delete();
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
