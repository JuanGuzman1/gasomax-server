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
        $petitioner = $request->petitioner;
        $status = $request->status;

        return $this->purchaseRequest->with(['quote', 'petitioner', 'files'])
            ->when($petitioner, function ($query) use ($petitioner) {
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
            $purchaseRequestRes = $this->purchaseRequest->with(['quote', 'petitioner', 'files'])
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
            $purchaseRequestRes = $this->purchaseRequest->with(['quote', 'petitioner', 'files'])
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
        $purchaseRequestRes = $this->purchaseRequest->with(['quote', 'petitioner'])
            ->where('id', $id)->firstOrFail();

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
        $userID = $request->user_id;
        return $this->purchaseRequest->with(['quote'])
            ->when($userID, function ($query) use ($userID) {
                return $query->where('petitioner_id', $userID);
            })->when($userID, function ($query) use ($userID) {
                return $query->whereHas('quote', function ($q) use ($userID) {
                    $q->where('petitioner_id', $userID);
                });
            })->where('balance', '>', 0)->get();
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
            $purchaseRequestRes = $this->purchaseRequest->with(['quote', 'petitioner', 'files'])
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

            $purchaseRequestRes = $this->purchaseRequest->with(['quote', 'petitioner', 'files'])
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

            $purchaseRequestRes = $this->purchaseRequest->with(['quote', 'petitioner', 'files'])
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
        $petitioner = $request->petitioner;


        return $this->purchaseRequest->with(['quote', 'petitioner'])
            ->when($userID, function ($query) use ($userID) {
                return $query->where('petitioner_id', $userID);
            })->when($userID, function ($query) use ($userID) {
                return $query->whereHas('quote', function ($q) use ($userID) {
                    $q->where('petitioner_id', $userID);
                });
            })->when($petitioner, function ($query) use ($petitioner) {
                return $query->whereHas('petitioner', function ($q) use ($petitioner) {
                    $q->where('name',  'like', '%' . $petitioner . '%');
                });
            })
            ->where('balance', '>', 0)
            ->paginate(10);
    }

    /**
     * Display the pending payment details.
     */
    public function getBalancePayments(string $id)
    {
        $pendingPayments = [];
        $pendingPayment = PurchaseRequest::findOrFail($id);
        array_unshift($pendingPayments, $pendingPayment);

        $last_id = $pendingPayment->purchase_request_pending_id;

        while ($last_id) {
            if ($last_id != null) {
                $pendingPayment = PurchaseRequest::findOrFail($last_id);

                $last_id = $pendingPayment->purchase_request_pending_id;
                array_unshift($pendingPayments, $pendingPayment);
            }
        }
        return $pendingPayments;
    }
}
