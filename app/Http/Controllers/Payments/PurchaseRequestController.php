<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payments\PurchaseRequest;
use App\Models\Payments\PurchaseRequestDetail;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseRequestController extends Controller
{

    use ApiResponseTrait;

    protected $purchaseRequest;
    public function __construct()
    {
        $this->purchaseRequest = new PurchaseRequest();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->purchaseRequest->with(['provider', 'petitioner', 'details'])
            ->orderBy('created_at', 'desc')->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $purchaseRequest = $this->purchaseRequest->create($request->all());
            if ($request->details) {
                foreach ($request->details as $d) {
                    $detail = new PurchaseRequestDetail([
                        'charge' => $d['charge'],
                        'concept' => $d['concept'],
                        'movementType' => $d['movementType'],
                        'observation' => $d['observation'],
                        'totalAmount' => $d['totalAmount'],
                        'paymentAmount' => $d['paymentAmount'],
                        'balance' => $d['totalAmount'] - $d['paymentAmount'],
                    ]);

                    $purchaseRequest->details()->save($detail);
                }
            }
            $purchaseRequestRes = $this->purchaseRequest->with(['provider', 'petitioner', 'details'])
                ->where('id', $purchaseRequest->id)->firstOrFail();
            return $this->successResponse($purchaseRequestRes);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->purchaseRequest->find($id);
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $purchaseRequest = $this->purchaseRequest->find($id);
            $purchaseRequest->update($request->all());
            if ($request->details) {
                $purchaseRequest->details()->delete();
                foreach ($request->details as $d) {
                    $detail = new PurchaseRequestDetail([
                        'charge' => $d['charge'],
                        'concept' => $d['concept'],
                        'movementType' => $d['movementType'],
                        'observation' => $d['observation'],
                        'totalAmount' => $d['totalAmount'],
                        'paymentAmount' => $d['paymentAmount'],
                        'balance' => $d['totalAmount'] - $d['paymentAmount'],
                    ]);

                    $purchaseRequest->details()->save($detail);
                }
            }
            $purchaseRequestRes = $this->purchaseRequest->with(['provider', 'petitioner', 'details'])
                ->where('id', $id)->firstOrFail();
            return $this->successResponse($purchaseRequestRes);
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
            $purchaseRequest = $this->purchaseRequest->find($id);
            $data = $purchaseRequest->delete();
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get PDF export.
     */
    public function exportPDF(string $id)
    {
        $purchaseRequestRes = $this->purchaseRequest->with(['provider', 'petitioner', 'details'])
            ->where('id', $id)->firstOrFail();

        $purchaseRequestRes->import = collect($purchaseRequestRes->details)->reduce(function ($a, $d) {
            return $a + $d->paymentAmount;
        }, 0);

        if ($purchaseRequestRes->paymentMethod === 'transference') {
            $account = $purchaseRequestRes->provider->accounts->firstWhere('primary', 1);
            $purchaseRequestRes->account = $account;
        }

        $pdf = PDF::loadView('pdf/purchaseRequest', ['purchaseRequest' => $purchaseRequestRes]);
        return $pdf->stream();
    }
}
