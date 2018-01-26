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
            'upload-file' => 'required|mimes:csv',
            // 'type' => 'required',
        ]);

        //@TODO - check type of csv import and store in variable we can use to fork off functionality

        // Push file to S3.
        $upload = $request->file('upload-file');
        $path = 'uploads/files/turbovote.csv';
        $csv = Reader::createFromPath($upload->getRealPath());
        $success = Storage::put($path, $csv);

        if (!$success) {
            throw new HttpException(500, 'Unable read and store file to S3.');
        }

        // We need to pass the file path and authenticated user role to
        // the queue job because it does not have access to these things otherwise.
        ImportTurboVotePosts::dispatch($path, auth()->user()->role);

        return redirect()->route('import.show')->with('status', 'Importing CSV!');
    }
}
