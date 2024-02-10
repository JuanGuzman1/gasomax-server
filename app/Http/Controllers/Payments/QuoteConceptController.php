<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payments\QuoteConcept;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class QuoteConceptController extends Controller
{
    use ApiResponseTrait;

    protected $quoteConcept;

    public function __construct()
    {
        $this->quoteConcept = new QuoteConcept();
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

    public function getCharges()
    {
        return $this->quoteConcept->select('charge')->groupBy('charge')->get();
    }

    /**
     * Display all of the resource for select.
     */
    public function getConceptsByCharge(Request $request)
    {
        $charge = $request->charge;
        return $this->quoteConcept->select('id', 'concept')
            ->where('charge', $charge)
            ->orderBy('created_at', 'desc')->get();
    }
}
