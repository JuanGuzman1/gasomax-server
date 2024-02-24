<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payments\PurchaseRequest;
use App\Models\Payments\PurchaseRequestDetail;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\Helpers;
use App\Models\Payments\PurchaseRequestObservation;

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
    public function index(Request $request)
    {
        $provider = $request->provider;
        $petitioner = $request->petitioner;
        $status = $request->status;

        return $this->purchaseRequest->with(['quote', 'petitioner', 'files'])
            ->when($provider, function ($query) use ($provider) {
                return $query->whereHas('provider', function ($q) use ($provider) {
                    $q->where('name',  'like', '%' . $provider . '%');
                });
            })->when($petitioner, function ($query) use ($petitioner) {
                return $query->whereHas('petitioner', function ($q) use ($petitioner) {
                    $q->where('name',  'like', '%' . $petitioner . '%');
                });
            })->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
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
                        'purchase_detail_pending_id' => $d['purchase_detail_pending_id']
                    ]);

                    if ($d['purchase_detail_pending_id']) {
                        $detailPending = PurchaseRequestDetail::find($d['purchase_detail_pending_id']);
                        $detailPending->balance = 0;
                        $detailPending->save();
                    }

                    $purchaseRequest->details()->save($detail);
                }
            }
            $purchaseRequestRes = $this->purchaseRequest->with(['provider', 'petitioner', 'details', 'files'])
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
            $purchaseRequestRes = $this->purchaseRequest->with(['provider', 'petitioner', 'details', 'files'])
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
        $functions = new Helpers();
        $purchaseRequestRes = $this->purchaseRequest->with(['provider', 'petitioner', 'details'])
            ->where('id', $id)->firstOrFail();

        $purchaseRequestRes->import = collect($purchaseRequestRes->details)->reduce(function ($a, $d) {
            return $a + $d->paymentAmount;
        }, 0);

        if ($purchaseRequestRes->paymentMethod === 'transference') {
            $account = $purchaseRequestRes->provider->accounts->firstWhere('primary', 1);
            $purchaseRequestRes->account = $account;
        }

        $pdf = PDF::loadView('pdf/purchaseRequest', [
            'purchaseRequest' => $purchaseRequestRes,
            'Helpers' => $functions
        ]);
        return $pdf->stream();
    }

    /**
     * Display the pending payment details by provider.
     */
    public function showPendingPaymentDetails(Request $request)
    {
        $providerID = $request->provider_id;
        $data = $this->purchaseRequest->with(['details'])
            ->when($providerID, function ($query) use ($providerID) {
                return $query->where('provider_id', $providerID);
            })->get();

        $details = [];
        foreach ($data as $d) {
            foreach ($d->details as $det) {
                if ($det->balance > 0) {
                    array_push($details, $det);
                }
            }
        }


        return $details;
    }

    /**
     * Reject the specified request in storage.
     */
    public function reject(Request $request, string $id)
    {
        try {
            $purchaseRequest = $this->purchaseRequest->find($id);
            $purchaseRequest->update([
                'status' => 'rejected'
            ]);
            if ($request->observation) {

                $observation = new PurchaseRequestObservation([
                    'message' => 'Motivo rechazo: ' . $request->observation,
                    'user_id' => $request->user_id
                ]);

                $purchaseRequest->observations()->save($observation);
            }
            $purchaseRequestRes = $this->purchaseRequest->with(['provider', 'petitioner', 'details', 'files'])
                ->where('id', $id)->firstOrFail();
            return $this->successResponse($purchaseRequestRes);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * approve the specified request in storage.
     */
    public function approve(Request $request, string $id)
    {
        try {
            $purchaseRequest = $this->purchaseRequest->find($id);
            $purchaseRequest->update([
                'status' => 'approved'
            ]);
            $observation = new PurchaseRequestObservation([
                'message' => 'Cotización aprobada',
                'user_id' => $request->user_id
            ]);
            $purchaseRequest->observations()->save($observation);

            $purchaseRequestRes = $this->purchaseRequest->with(['provider', 'petitioner', 'details', 'files'])
                ->where('id', $id)->firstOrFail();
            return $this->successResponse($purchaseRequestRes);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * pay the specified request in storage.
     */
    public function pay(Request $request, string $id)
    {
        try {
            $purchaseRequest = $this->purchaseRequest->find($id);
            $purchaseRequest->update([
                'paymentDate' => $request->paymentDate,
                'status' => 'paid'
            ]);
            $observation = new PurchaseRequestObservation([
                'message' => 'Cotización pagada',
                'user_id' => $request->user_id
            ]);
            $purchaseRequest->observations()->save($observation);

            $purchaseRequestRes = $this->purchaseRequest->with(['provider', 'petitioner', 'details', 'files'])
                ->where('id', $id)->firstOrFail();
            return $this->successResponse($purchaseRequestRes);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }


    /**
     * Display the pending payment details.
     */
    public function getPendingPayments(Request $request)
    {
        $userID = $request->user_id;
        $provider = $request->provider;
        $petitioner = $request->petitioner;


        $data = $this->purchaseRequest->with(['details', 'petitioner', 'provider'])
            ->when($userID, function ($query) use ($userID) {
                return $query->where('petitioner_id', $userID);
            })->when($provider, function ($query) use ($provider) {
                return $query->whereHas('provider', function ($q) use ($provider) {
                    $q->where('name',  'like', '%' . $provider . '%');
                });
            })->when($petitioner, function ($query) use ($petitioner) {
                return $query->whereHas('petitioner', function ($q) use ($petitioner) {
                    $q->where('name',  'like', '%' . $petitioner . '%');
                });
            })
            ->whereHas('details', function ($query) {
                $query->where('balance', '>', 0);
            })->paginate(10);


        foreach ($data as $d) {
            $filtered = $d->details->filter(function ($item) {
                if ($item->balance > 0) {
                    return $item;
                }
            })->values();
            $d->detailsFiltered = $filtered;
        }

        return $data;
    }

    /**
     * Display the pending payment details.
     */
    public function getBalancePayments(string $id)
    {
        $pendingPayments = [];
        $pendingDetail = PurchaseRequestDetail::findOrFail($id);
        array_unshift($pendingPayments, $pendingDetail);

        $last_id = $pendingDetail->purchase_detail_pending_id;

        while ($last_id) {
            if ($last_id != null) {
                $pendingDetail = PurchaseRequestDetail::findOrFail($last_id);

                $last_id = $pendingDetail->purchase_detail_pending_id;
                array_unshift($pendingPayments, $pendingDetail);
            }
        }
        return $pendingPayments;
    }
}
