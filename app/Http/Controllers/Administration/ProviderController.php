<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administration\Provider;
use App\Exports\ProvidersExport;
use App\Models\Administration\Bank;
use App\Models\Administration\ProviderAccount;
use App\Traits\ApiResponseTrait;
use Maatwebsite\Excel\Facades\Excel;

class ProviderController extends Controller
{
    use ApiResponseTrait;

    protected $provider;
    public function __construct()
    {
        $this->provider = new Provider();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $name = $request->name;
        $contact = $request->contact;
        $rfc = $request->rfc;

        return $this->provider->with(['files', 'accounts'])->when($name, function ($query) use ($name) {
            return $query->where('name',  'like', '%' . $name . '%');
        })->when($contact, function ($query) use ($contact) {
            return $query->where('contact',  'like', '%' . $contact . '%');
        })->when($rfc, function ($query) use ($rfc) {
            return $query->where('rfc', 'like', '%' . $rfc . '%');
        })->orderBy('created_at', 'desc')->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $provider = $this->provider->create($request->all());
            if ($request->bankAccounts) {
                foreach ($request->bankAccounts as $BA) {
                    $providerAccount = new ProviderAccount([
                        'bankAccount' => $BA['bankAccount'],
                        'clabe' => $BA['clabe'],
                        'primary' => $BA['primary'] ?? false,
                    ]);
                    $bank = Bank::find($BA['bank_id']);
                    $providerAccount->bank()->associate($bank);
                    $provider->accounts()->save($providerAccount);
                }
            }
            $providerRes = $this->provider->with(['files', 'accounts'])->where('id', $provider->id)->firstOrFail();
            return $this->successResponse($providerRes);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->provider->find($id);
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $provider = $this->provider->find($id);
            $provider->update($request->all());
            if ($request->bankAccounts) {
                $provider->accounts()->delete();
                foreach ($request->bankAccounts as $BA) {
                    $providerAccount = new ProviderAccount([
                        'bankAccount' => $BA['bankAccount'],
                        'clabe' => $BA['clabe'],
                        'primary' => $BA['primary'] ?? false,
                    ]);
                    $bank = Bank::find($BA['bank_id']);
                    $providerAccount->bank()->associate($bank);
                    $provider->accounts()->save($providerAccount);
                }
            }
            $providerRes = $this->provider->with(['files', 'accounts'])->where('id', $id)->firstOrFail();
            return $this->successResponse($providerRes);
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
            $provider = $this->provider->find($id);
            $data = $provider->delete();
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request)
    {
        $filters = [
            'name' => $request->name,
            'contact' => $request->contact,
            'rfc' => $request->rfc
        ];


        return Excel::download(new ProvidersExport($filters), 'Proveedores.xlsx');
    }

    /**
     * Display all of the resource for select.
     */
    public function select()
    {
        return $this->provider->orderBy('created_at', 'desc')->get();
    }

    /**
     * Display all of the resource for select.
     */
    public function selectAccountsByProvider(String $id)
    {
        return ProviderAccount::with(['bank'])->where('provider_id', $id)->orderBy('created_at', 'desc')->get();
    }
}
