<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administration\Bank;
use App\Traits\ApiResponseTrait;

class BankController extends Controller
{
    use ApiResponseTrait;

    protected $bank;
    public function __construct()
    {
        $this->bank = new Bank();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $name = $request->name;

        return $this->bank->when($name, function ($query) use ($name) {
            return $query->where('name',  'like', '%' . $name . '%');
        })->orderBy('created_at', 'desc')->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $this->bank->create($request->all());
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
        $data = $this->bank->find($id);
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $bank = $this->bank->find($id);
            $bank->update($request->all());
            return $this->successResponse($bank);
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
            $bank = $this->bank->find($id);
            $data = $bank->delete();
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
        return $this->bank->orderBy('created_at', 'desc')->get();
    }
}
