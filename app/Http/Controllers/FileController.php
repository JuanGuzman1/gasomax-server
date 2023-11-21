<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\File;
use Error;
use Exception;

class FileController extends Controller
{

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
            'name' => basename($path),
            'tag' => $request->tag,
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
        //
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
}
