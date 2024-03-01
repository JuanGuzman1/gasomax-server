<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payments\PurchaseRequest;
use App\Models\Payments\Quote;
use App\Models\Payments\QuoteFile;
use App\Models\Payments\QuoteObservation;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    use ApiResponseTrait;

    protected $quote;

    public function __construct()
    {
        $this->quote = new Quote();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $provider = $request->provider;
        $petitioner = $request->petitioner;
        $status = $request->status;

        return $this->quote->with(['provider', 'petitioner', 'quoteConcept', 'files', 'images'])
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
            $quote = $this->quote->create($request->all());
            $quoteRes = $this->quote->with(['provider', 'petitioner', 'quoteConcept', 'files', 'images'])
                ->where('id', $quote->id)->firstOrFail();
            return $this->successResponse($quoteRes);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->quote->find($id);
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $quote = $this->quote->find($id);
            $quote->update($request->all());


            //approve or rejected
            if ($request->observation) {
                $observation = new QuoteObservation([
                    'message' => $request->observation,
                    'user_id' => $quote->petitioner_id
                ]);

                $quote->observations()->save($observation);
            }

            //approve
            if ($request->selectedQuoteID) {
                $quoteFile = QuoteFile::find($request->selectedQuoteID);
                if ($quoteFile) {
                    $quoteFile->selectedQuoteFile = 1;
                    $quoteFile->save();
                }
            }

            //authorization
            if ($request->status === 'authorized' || $request->status === 'rejected') {
                $status = $request->status === 'authorized' ? 'AUTORIZADO' : 'RECHAZADO';
                $observation = new QuoteObservation([
                    'message' => $status . ' por ' . Auth::user()->name,
                    'user_id' => Auth::user()->id
                ]);

                $quote->observations()->save($observation);
            }


            $quoteRes = $this->quote->with(['provider', 'petitioner', 'quoteConcept', 'files', 'images'])
                ->where('id', $id)->firstOrFail();
            return $this->successResponse($quoteRes);
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
            $quote = $this->quote->find($id);
            $data = $quote->delete();
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Send pay the specified resource in storage.
     */
    public function sendPay(Request $request, string $id)
    {
        try {
            $quote = $this->quote->find($id);
            $quote->update($request->all());


            if ($request->status === 'sentPay') {
                $observation = new QuoteObservation([
                    'message' => 'ENVIADO A PROCESO DE PAGO' . ' por ' . Auth::user()->name,
                    'user_id' => Auth::user()->id
                ]);
                $quote->observations()->save($observation);
            }

            $paymentRequest = new PurchaseRequest([
                'title' => $quote->title,
                'quote_id' => $quote->id,
                'totalAmount' => $quote->approvedAmount,
                'paymentAmount' => $quote->approvedAmount,
                'petitioner_id' => Auth::user()->id,
                'provider_id' => $request->provider_id,
                'provider_account_id' => $request->provider_account_id,
                'paymentWithoutInvoice' => $request->paymentWithoutInvoice,
                'fromQuote' => true
            ]);
            $paymentRequest->save();

            $quoteRes = $this->quote->with(['provider', 'petitioner', 'quoteConcept', 'files', 'images'])
                ->where('id', $id)->firstOrFail();

            $data = [
                'quote' => $quoteRes,
                'purchaseRequest' => $paymentRequest
            ];


            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
