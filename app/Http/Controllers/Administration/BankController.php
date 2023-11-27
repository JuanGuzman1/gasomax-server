<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administration\Bank;

class BankController extends Controller
{

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
        })->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->bank->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->bank->find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bank = $this->bank->find($id);
        $bank->update($request->all());
        return $bank;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bank = $this->bank->find($id);
        return $bank->delete();
    }
}
