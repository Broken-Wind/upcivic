<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Assignment;
use App\File;
use App\Http\Requests\StoreFile;
use App\InstructorAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InstructorAssignmentFilesController extends Controller
{
    //
    public function store(StoreFile $request, InstructorAssignment $assignment)
    {
        $validated = $request->validated();

        if ($request->hasFile('files')) {
            foreach($validated['files'] as $document) {
                $path = Storage::putFile('tasks', $document);
                $file = File::make([
                    'path' => $path,
                    'filename' => $document->getClientOriginalName()
                ]);
                $file->user_id = Auth::user()->id;
                $file->organization_id = tenant()->organization_id;
                $file->entity_type = InstructorAssignment::class;
                $file->entity_id = $assignment->id;
                $file->save();
            }
        }
        return back()->withSuccess('Files uploaded.');
    }
}
