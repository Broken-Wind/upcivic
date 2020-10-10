<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    //
    public function download($file)
    {
        $file = File::withoutGlobalScopes()->find($file);
        if ($file->canDownload(Auth::user())) {
            return Storage::download($file->path, $file->filename);
        }
        abort(401);
    }
    public function destroy(File $file)
    {
        if ($file->canDelete(Auth::user())) {
            $file->delete();
            return back()->withSuccess('File deleted.');
        }
        abort(401);
    }
}
