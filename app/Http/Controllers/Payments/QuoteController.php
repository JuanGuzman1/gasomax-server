<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payments\Quote;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

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

        return $this->quote->with(['provider', 'petitioner', 'quoteConcept', 'files'])
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
            $quoteRes = $this->quote->with(['provider', 'petitioner', 'quoteConcept', 'files'])
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
            $quoteRes = $this->quote->with(['provider', 'petitioner', 'quoteConcept', 'files'])
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
}
