<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administration\Provider;

class ProviderController extends Controller
{

    protected $provider;
    public function __construct()
    {
        $this->provider = new Provider();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->provider->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->provider->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->provider->find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $provider = $this->provider->find($id);
        $provider->update($request->all());
        return $provider;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $provider = $this->provider->find($id);
        return $provider->delete();
    }
}
