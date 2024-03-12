<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


class ConfigurationController extends Controller
{

    use ApiResponseTrait;

    protected $config;

    public function __construct()
    {
        $this->config = new Configuration();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Carbon::setLocale('es');

        $config = $this->config->first();

        if ($config) {

            $hrUpdated = $config->updated_at;
            $now = Carbon::now()->setTimezone('America/Mexico_City');

            $differenceInSeconds = $now->diffInSeconds($hrUpdated);

            if ($differenceInSeconds > $config->token_dropbox_expires_in) {
                $response = Http::asForm()->post('https://api.dropboxapi.com/oauth2/token', [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $config->token_dropbox_refresh,
                    'client_id' => 'g3bm3pdzcbycsb7',
                    'client_secret' =>  'wlhthat651rnf5i',
                ]);

                if ($response->successful()) {
                    $resApi = $response->json();
                    $config->token_dropbox = $resApi['access_token'];
                    $config->token_dropbox_expires_in = $resApi['expires_in'];
                    $config->save();
                }
            }
            putenv("DROPBOX_AUTH_TOKEN=$config->token_dropbox");
            Artisan::call('optimize');
        }

        return $this->successResponse($config);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
