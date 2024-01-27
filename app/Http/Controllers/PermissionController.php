<?php

namespace App\Http\Controllers;

use App\Models\Permission;
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
}
