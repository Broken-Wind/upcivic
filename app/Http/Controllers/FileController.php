<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    //
    public function download(File $file)
    {
        return Storage::download($file->path, $file->filename);
    }
}
