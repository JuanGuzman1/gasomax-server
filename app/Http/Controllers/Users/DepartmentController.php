<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Users\Department;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

class DepartmentController extends Controller
{

    use ApiResponseTrait;

    protected $department;
    public function __construct()
    {
        $this->department = new Department();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $name = $request->name;

        return $this->department->when($name, function ($query) use ($name) {
            return $query->where('name',  'like', '%' . $name . '%');
        })->orderBy('created_at', 'desc')->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $this->department->create($request->all());
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
        $data = $this->department->find($id);
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $department = $this->department->find($id);
            $department->update($request->all());
            return $this->successResponse($department);
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
            $department = $this->department->find($id);
            $data = $department->delete();
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display all of the resource for select.
     */
    public function select()
    {
        return $this->department->orderBy('created_at', 'desc')->get();
    }
}
