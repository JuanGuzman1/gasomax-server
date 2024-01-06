<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payments\PurchaseRequestObservation;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

class PurchaseRequestObservationController extends Controller
{

    use ApiResponseTrait;

    protected $purchaseRequestObservation;
    public function __construct()
    {
        $this->purchaseRequestObservation = new PurchaseRequestObservation();
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->purchaseRequestObservation->with('user')
            ->where('purchase_request_id', $request->purchase_request_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $purchaseRequestObservation = $this->purchaseRequestObservation->create($request->all());
            $purchaseRequestObservationRes = $this->purchaseRequestObservation->with('user')
                ->where('id', $purchaseRequestObservation->id)->firstOrFail();
            return $this->successResponse($purchaseRequestObservationRes);
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
            $purchaseRequestObservation = $this->purchaseRequestObservation->find($id);
            $purchaseRequestObservation->update($request->all());
            return $this->successResponse($purchaseRequestObservation);
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
            $purchaseRequestObservation = $this->purchaseRequestObservation->find($id);
            $data = $purchaseRequestObservation->delete();
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
