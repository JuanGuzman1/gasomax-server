<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Administration\Unit;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    use ApiResponseTrait;

    protected $unit;

    public function __construct()
    {
        $this->unit = new Unit();
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

    public function getLines()
    {
        return $this->unit->select('line')->groupBy('line')->get();
    }

    /**
     * Display all of the resource for select.
     */
    public function getUnitsByLine(Request $request)
    {
        $line = $request->line;
        return $this->unit->select('id', 'unit')
            ->where('line', $line)
            ->orderBy('created_at', 'desc')->get();
    }
}
