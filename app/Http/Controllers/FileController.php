<?php

namespace App\Http\Controllers;

use Storage;

class FileController extends Controller
{
    public function getFile($category, $filename)
    {
        $path = "{$category}/{$filename}";

        if (! Storage::disk('private')->exists($path)) {

            abort(404, 'File Not Found.');
        }

        $file = Storage::disk('private')->get($path);
        $type = Storage::disk('private')->mimeType($path);

        return response($file, 200)->header('Content-Type', $type);

    }
}
