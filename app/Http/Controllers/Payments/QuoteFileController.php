<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payments\QuoteFile;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuoteFileController extends Controller
{
    use ApiResponseTrait;

    protected $quoteFile;

    public function __construct()
    {
        $this->quoteFile = new QuoteFile();
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

        return $this->quoteFile->create([
            'localName' => $request->localName,
            'name' => basename($path),
            'tag' => $request->tag,
            'description' => $request->description,
            'extension' => $request->file('file')->getClientOriginalExtension(),
            'size' => $size,
            'path' => $path,
            'quote_id' => $request->quote_id,
            'provider' => $request->provider,
            'amount' => $request->amount,
            'deliveryDate' => $request->deliveryDate,
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
        $file = $this->quoteFile->find($id);
        $filesystem = Storage::disk('dropbox');
        $filesystem->delete($file->path);

        return $file->delete();
    }

    /**
     * find to remove by quote_id resource from storage.
     */
    public function destroyByQuote(string $id)
    {
        $files = $this->quoteFile->select('id')->where('quote_id', $id)->get();
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

            $file = $this->quoteFile->find($id);

            $path = $file->path;
            $localFilePath = storage_path('app/archivo_temporal.pdf');

            file_put_contents($localFilePath, $filesystem->read($path));

            return response()->download($localFilePath, 'archivo_temporal.pdf')->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }
}
