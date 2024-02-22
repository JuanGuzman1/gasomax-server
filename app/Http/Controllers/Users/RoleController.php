<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Users\Role;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    use ApiResponseTrait;

    protected $role;

    public function __construct()
    {
        $this->role = new Role();
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
     * Display all of the resource for select.
     */
    public function select()
    {
        return $this->role->orderBy('created_at', 'desc')->get();
    }
}
