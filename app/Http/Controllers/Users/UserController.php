<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Users\User;
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
        //TODO GET PERMISSIONS IN ANOTHER FUNCTION
        return $this->user->with(['department', 'modules', 'permissions'])->when($name, function ($query) use ($name) {
            return $query->where('name',  'like', '%' . $name . '%');
        })->orderBy('created_at', 'desc')->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $this->user->create($request->all());
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
            return $this->successResponse($user);
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
