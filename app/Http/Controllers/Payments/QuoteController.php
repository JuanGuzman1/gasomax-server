<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payments\PurchaseRequest;
use App\Models\Payments\Quote;
use App\Models\Payments\QuoteFile;
use App\Models\Payments\QuoteObservation;
use App\Models\Users\User;
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
        $user = User::find(Auth::user()->id);
        $provider = $request->provider;
        $petitioner = $request->petitioner;
        $status = $request->status;
        $role = $user->role->name;
        $department = $user->department->name;
        $permissions = [];

        foreach ($user->permissions as $p) {
            array_push($permissions, $p->permission);
        };


        $data = $this->quote->with(['provider', 'petitioner', 'quoteConcept', 'files', 'images', 'payments'])
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
            ->orderBy('created_at', 'desc');


        if (!array_search('upload.quote', array_column($permissions, 'name'))) {
            $data->where('petitioner_id', $user->id);

            //GERENTES
            if ($role === 'GERENTE') {
                //CONTRALORIA
                if ($department === 'CONTRALORIA') {
                    $data->orWhereHas('petitioner', function ($query) {
                        $query->whereHas('role', function ($query) {
                            $query->whereIn('name', array('EJECUTIVO'));
                        })->whereHas('department', function ($query) {
                            $query->whereIn('name', array('GESTORIA', 'COMPRAS'));
                        });
                        $query->orWhereHas('role', function ($query) {
                            $query->whereIn('name', array('JEFE'));
                        })->whereHas('department', function ($query) {
                            $query->whereIn('name', array('MAXSTORE'));
                        });
                    })->where('rejectQuotes', false)->whereNotIn('status', array('sent', 'inprogress'));
                }
                //OPERACIONES DE ESTACIONES
                if ($department === 'OPERACION') {
                    $data->orWhereHas('petitioner', function ($query) {
                        $query->whereHas('role', function ($query) {
                            $query->whereIn('name', array('EJECUTIVO'));
                        })->whereHas('department', function ($query) {
                            $query->whereIn('name', array(
                                'COMPRAS', 'GESTORIA'
                            ));
                        });
                        $query->orWhereHas('role', function ($query) {
                            $query->whereIn('name', array('JEFE', 'AUXILIAR'));
                        })->whereHas('department', function ($query) {
                            $query->whereIn('name', array(
                                'OPERACION'
                            ));
                        });

                        $query->orWhereHas('role', function ($query) {
                            $query->whereIn('name', array('COORDINADOR'));
                        })->whereHas('department', function ($query) {
                            $query->whereIn('name', array(
                                'SISTEMAS', 'NORMATIVIDAD'
                            ));
                        });
                    })->where('rejectQuotes', false)->whereNotIn('status', array('sent', 'inprogress'));
                }
            }

            //COORDINADORES
            if ($role === 'COORDINADOR') {
                if ($department === 'MTTO') {
                    $data->orWhereHas('petitioner', function ($query) {
                        $query->whereHas('role', function ($query) {
                            $query->whereIn('name', array('JEFE'));
                        })->whereHas('department', function ($query) {
                            $query->whereIn('name', array('MTTO'));
                        });
                    })->where('rejectQuotes', false)->whereNotIn('status', array('sent', 'inprogress'));
                }
            }


            //DIRECCION
            if ($department === 'DIRECCION') {

                if ($role === 'DIRECCION GENERAL') {
                    //direct
                    $data->orWhereHas('petitioner', function ($query) {
                        $query->whereHas('role', function ($query) {
                            $query->whereIn('name', array('JEFE'));
                        })->whereHas('department', function ($query) {
                            $query->whereIn('name', array('RESTAURANTE'));
                        });
                    })->where('rejectQuotes', false)->whereNotIn('status', array('sent', 'inprogress'));

                    //withVoBo
                    $data->orWhereHas('petitioner', function ($query) {
                        $query->whereHas('role', function ($query) {
                            $query->whereIn('name', array('EJECUTIVO'));
                        })->whereHas('department', function ($query) {
                            $query->whereIn('name', array('COMPRAS', 'GESTORIA'));
                        });
                        $query->orWhereHas('role', function ($query) {
                            $query->whereIn('name', array('COORDINADOR'));
                        })->whereHas('department', function ($query) {
                            $query->whereIn('name', array('SISTEMAS'));
                        });
                        $query->orWhereHas('role', function ($query) {
                            $query->whereIn('name', array('GERENTE'));
                        })->whereHas('department', function ($query) {
                            $query->whereIn('name', array('DH', 'CONTRALORIA'));
                        });
                    })->where('rejectQuotes', false)->whereNotIn('status', array('sent', 'inprogress', 'approved'));
                }

                if ($role === 'SUBDIRECCION GENERAL') {
                    //direct
                    $data->orWhereHas('petitioner', function ($query) {
                        $query->whereHas('role', function ($query) {
                            $query->whereIn('name', array('COORDINADOR'));
                        })->whereHas('department', function ($query) {
                            $query->whereIn('name', array('MKT', 'MTTO', 'MAXSTORE'));
                        });
                        $query->orWhereHas('role', function ($query) {
                            $query->whereIn('name', array('JEFE'));
                        })->whereHas('department', function ($query) {
                            $query->whereIn('name', array('CARWASH'));
                        });
                    })->where('rejectQuotes', false)->whereNotIn('status', array('sent', 'inprogress'));


                    //withVoBo
                    $data->orWhereHas('petitioner', function ($query) {
                        $query->whereHas('role', function ($query) {
                            $query->whereIn('name', array('JEFE'));
                        })->whereHas('department', function ($query) {
                            $query->whereIn('name', array('OPERACION', 'MAXSTORE', 'MTTO'));
                        });
                        $query->orWhereHas('role', function ($query) {
                            $query->whereIn('name', array('AUXILIAR'));
                        })->whereHas('department', function ($query) {
                            $query->whereIn('name', array('OPERACION'));
                        });
                        $query->orWhereHas('role', function ($query) {
                            $query->whereIn('name', array('COORDINADOR'));
                        })->whereHas('department', function ($query) {
                            $query->whereIn('name', array('NORMATIVIDAD'));
                        });
                        $query->orWhereHas('role', function ($query) {
                            $query->whereIn('name', array('GERENTE'));
                        })->whereHas('department', function ($query) {
                            $query->whereIn('name', array('OPERACION'));
                        });
                    })->where('rejectQuotes', false)->whereNotIn('status', array('sent', 'inprogress', 'approved'));
                }
            }
        }


        return $data->paginate(10);
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
                    'user_id' => Auth::user()->id
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
                    'message' => $status . ' POR ' . strtoupper(Auth::user()->name),
                    'user_id' => Auth::user()->id
                ]);

                $quote->observations()->save($observation);
            }

            if ($request->status === 'ok') {
                $observation = new QuoteObservation([
                    'message' => 'VoBo POR ' . strtoupper(Auth::user()->name),
                    'user_id' => Auth::user()->id
                ]);
                $quote->observations()->save($observation);
            }

            //rejected
            if ($request->status === 'rejected' && $request->selectedQuoteID) {
                $quoteFile = QuoteFile::find($request->selectedQuoteID);
                if ($quoteFile) {
                    $quoteFile->selectedQuoteFile = 0;
                    $quoteFile->save();
                }
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
                    'message' => 'ENVIADO A PROCESO DE PAGO' . ' POR ' . strtoupper(Auth::user()->name),
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

            $quoteRes = $this->quote->with(['provider', 'petitioner', 'quoteConcept', 'files', 'images', 'payments'])
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
