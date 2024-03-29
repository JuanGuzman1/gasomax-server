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
use App\Models\Payments\Quote;
use App\Models\Payments\QuoteObservation;
use Illuminate\Support\Facades\Auth;

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

            if ($request->totalPaymentApproved) {
                $observation = new PurchaseRequestObservation([
                    'message' => 'PAGO APROBADO POR ' . strtoupper(Auth::user()->name),
                    'user_id' => Auth::user()->id
                ]);
                $purchaseRequest->status = 'approved';
                $purchaseRequest->paymentAmount = $purchaseRequest->totalAmount;
                $purchaseRequest->observations()->save($observation);
                $purchaseRequest->save();
            }

            if ($request->totalPaymentModified) {
                $observation = new PurchaseRequestObservation([
                    'message' => 'PAGO APROBADO Y MODIFICADO POR ' . strtoupper(Auth::user()->name),
                    'user_id' => Auth::user()->id
                ]);
                $purchaseRequest->status = 'approved';
                $purchaseRequest->balance = $purchaseRequest->totalAmount - $purchaseRequest->paymentAmount;
                $purchaseRequest->observations()->save($observation);
                $purchaseRequest->save();
            }

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
     * Display the pending payment details .
     */
    public function showPendingPaymentDetails(Request $request)
    {
        $userID = $request->user_id;
        $dataBalance = $this->purchaseRequest->with(['quote'])
            ->where('petitioner_id', $userID)->where('status', 'paid')->where('balance', '>', 0)
            ->orWhereHas('quote', function ($q) use ($userID) {
                $q->orWhere('petitioner_id', $userID);
            })->where('status', 'paid')->where('balance', '>', 0)->get();

        $data = [];

        foreach ($dataBalance as $db) {
            $dataAll = $this->purchaseRequest->where('purchase_request_pending_id', $db->id)->first();
            if (!$dataAll) {
                array_push($data, $db);
            }
        }

        return $data;
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
                    'message' => 'MOTIVO DE RECHAZO: ' . $request->observation,
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
                'message' => 'SOLICITUD PAGADA POR ' . strtoupper(Auth::user()->name),
                'user_id' => $request->user_id
            ]);
            $purchaseRequest->observations()->save($observation);

            //Quote update to paid
            if ($purchaseRequest->quote_id && !$purchaseRequest->purchase_request_pending_id) {
                $quote = Quote::find($purchaseRequest->quote_id);
                if ($quote) {
                    $quote->status = 'paid';
                    $quote->save();

                    $observationQuote = new QuoteObservation([
                        'message' => 'COTIZACION PAGADA POR ' . strtoupper(Auth::user()->name),
                        'user_id' => $request->user_id
                    ]);
                    $quote->observations()->save($observationQuote);
                }
            }


            if ($purchaseRequest->purchase_request_pending_id) {
                $purchaseRequestOriginal = $this->purchaseRequest->find($purchaseRequest->purchase_request_pending_id);
                $purchaseRequestOriginal->balance = 0;
                $purchaseRequestOriginal->save();
            }

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
        $userID = Auth::user()->id;
        $petitioner = $request->petitioner;


        return $this->purchaseRequest->with(['quote', 'petitioner'])
            ->where('petitioner_id', $userID)
            ->where('balance', '>', 0)
            ->where('status', 'paid')
            ->orWhereHas('quote', function ($q) use ($userID) {
                $q->orWhere('petitioner_id', $userID);
            })
            ->when($petitioner, function ($query) use ($petitioner) {
                return $query->whereHas('petitioner', function ($q) use ($petitioner) {
                    $q->where('name',  'like', '%' . $petitioner . '%');
                });
            })
            ->where('balance', '>', 0)
            ->where('status', 'paid')
            ->paginate(10);
    }

    /**
     * Display the pending payment details.
     */
    public function getBalancePayments(string $id)
    {
        $pendingPayments = [];
        $pendingPayment = PurchaseRequest::with('quote')->findOrFail($id);
        array_unshift($pendingPayments, $pendingPayment);

        $last_id = $pendingPayment->purchase_request_pending_id;

        while ($last_id) {
            if ($last_id != null) {
                $pendingPayment = PurchaseRequest::with('quote')->findOrFail($last_id);

                $last_id = $pendingPayment->purchase_request_pending_id;
                array_unshift($pendingPayments, $pendingPayment);
            }
        }
        return $pendingPayments;
    }
}
