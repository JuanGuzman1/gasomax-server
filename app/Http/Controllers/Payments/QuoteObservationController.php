<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payments\QuoteObservation;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class QuoteObservationController extends Controller
{


    use ApiResponseTrait;

    protected $quoteObservation;
    public function __construct()
    {
        $this->quoteObservation = new QuoteObservation();
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->quoteObservation->with('user')
            ->where('quote_id', $request->quote_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $quoteObservation = $this->quoteObservation->create($request->all());
            $quoteObservationRes = $this->quoteObservation->with('user')
                ->where('id', $quoteObservation->id)->firstOrFail();
            return $this->successResponse($quoteObservationRes);
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
        try {
            $quoteObservation = $this->quoteObservation->find($id);
            $quoteObservation->update($request->all());
            return $this->successResponse($quoteObservation);
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
            $quoteObservation = $this->quoteObservation->find($id);
            $data = $quoteObservation->delete();
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
