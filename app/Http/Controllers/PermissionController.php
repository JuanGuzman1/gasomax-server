<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Users\User;
use App\Models\Users\UserPermission;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    use ApiResponseTrait;

    protected $permission;

    public function __construct()
    {
        $this->permission = new Permission();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->permission->with(['module'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Assign permissions to user.
     */
    public function assign(Request $request)
    {
        try {
            $permissions = $request->permissions;
            $user = User::find($request->user_id);
            $user->permissions()->delete();
            foreach ($permissions as $p) {
                $permission = Permission::where('id', $p)->first();
                if ($permission) {
                    $userPermission = new UserPermission([
                        'user_id' => $request->user_id,
                        'permission_id' => $p
                    ]);
                    $user->permissions()->save($userPermission);
                }
            }
            $userRes = User::with(['department', 'modules', 'permissions'])->where('id', $user->id)->first();
            return $this->successResponse($userRes);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
