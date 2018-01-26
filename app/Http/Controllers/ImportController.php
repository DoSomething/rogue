<?php

namespace Rogue\Http\Controllers;

use League\Csv\Reader;
use Illuminate\Http\Request;
use Rogue\Jobs\ImportTurboVotePosts;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ImportController extends Controller
{
    /**
     * Instantiate a new ImportController instance.
     *
     * @param Rogue\Services\Registrar $registrar
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the upload form.
     */
    public function show()
    {
        return view('pages.upload');
    }

    /**
     * Import the uploaded file.
     *
     * @param  Request $request
     */
    public function store(Request $request)
    {
        $request->validate([
            'upload-file' => 'required',
            // 'type' => 'required',
        ]);

        //@TODO - check type of csv import and store in variable we can use to fork off functionality

        // Push file to S3.
        $upload = $request->file('upload-file');
        $path = 'uploads/files/turbovote.csv';
        $csv = Reader::createFromPath($upload->getRealPath());
        $success = Storage::put($path, $csv);

        if (!$success) {
            throw new HttpException(500, 'Unable to save image to S3.');
        }

        ImportTurboVotePosts::dispatch($path, auth()->user()->role);
    }
}
