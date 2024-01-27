<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Users\User;
use App\Models\Users\UserModule;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    use ApiResponseTrait;

    protected $module;

    public function __construct()
    {
        $this->module = new Module();
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->module->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $this->module->create($request->all());
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
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
     * Assign modules to user.
     */
    public function assign(Request $request)
    {
        try {
            $modules = $request->modules;
            $user = User::find($request->user_id);
            $user->modules()->delete();
            foreach ($modules as $m) {
                $module = Module::where('submodule', $m)->first();
                if ($module) {
                    $userModule = new UserModule([
                        'user_id' => $request->user_id,
                        'module_id' => $module->id
                    ]);
                    $user->modules()->save($userModule);
                }
            }
            $userRes = User::with(['department', 'modules'])->where('id', $user->id)->first();
            return $this->successResponse($userRes);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
