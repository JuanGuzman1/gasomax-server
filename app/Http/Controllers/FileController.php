<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\File;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Http;
use Exception;

class FileController extends Controller
{
    use ApiResponseTrait;

    protected $file;
    public function __construct()
    {
        $this->file = new File();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $filesystem = Storage::disk('dropbox');

        $path = $filesystem->put(
            'storage',
            $request->file('file'),
        );

        $size = $filesystem->size($path) / 1000;

        return $this->file->create([
            'localName' => $request->localName,
            'name' => basename($path),
            'tag' => $request->tag,
            'description' => $request->description,
            'extension' => $request->file('file')->getClientOriginalExtension(),
            'size' => $size,
            'path' => $path,
            'fileable_id' => $request->fileable_id,
            'fileable_type' => $request->fileable_type
        ]);
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
        $file = $this->file->find($id);
        $filesystem = Storage::disk('dropbox');
        $filesystem->delete($file->path);

        return $file->delete();
    }

    /**
     * find to remove by fileable_id resource from storage.
     */
    public function destroyByModel(string $id, string $model)
    {
        $files = $this->file->select('id')->where('fileable_id', $id)->where('fileable_type', $model)->get();
        foreach ($files as $f) {
            $this->destroy($f->id);
        }
    }


    /**
     * Download the specified resource from storage.
     */
    public function download(string $id)
    {

        try {
            $filesystem = Storage::disk('dropbox');

            $file = $this->file->find($id);

            $path = $file->path;
            $localFilePath = storage_path('app/archivo_temporal.pdf');

            file_put_contents($localFilePath, $filesystem->read($path));

            return response()->download($localFilePath, 'archivo_temporal.pdf')->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }


    public function getTokenWCode(Request $request)
    {
        try {
            $response = Http::asForm()->post('https://api.dropboxapi.com/oauth2/token', [
                'grant_type' => 'authorization_code',
                'code' => $request->code,
                'client_id' => 'g3bm3pdzcbycsb7',
                'client_secret' =>  'wlhthat651rnf5i',
                'redirect_uri' => $request->redirect_uri
            ]);

            if ($response->successful()) {
                $resApi = $response->json();
                $config = Configuration::first();
                if ($config) {
                    $config->authorization_code_dropbox = $request->code;
                    $config->token_dropbox = $resApi['access_token'];
                    $config->token_dropbox_refresh = $resApi['refresh_token'];
                    $config->token_dropbox_expires_in = $resApi['expires_in'];
                } else {
                    $config = new Configuration();
                    $config->authorization_code_dropbox = $request->code;
                    $config->token_dropbox = $resApi['access_token'];
                    $config->token_dropbox_refresh = $resApi['refresh_token'];
                    $config->token_dropbox_expires_in = $resApi['expires_in'];
                }

                $config->save();

                return $this->successResponse($resApi);
            } else {
                return $this->errorResponse($response->json());
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
