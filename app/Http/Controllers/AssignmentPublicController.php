<?php

namespace App\Http\Controllers;

use App\File;
use App\Assignment;
use App\Program;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePublicFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentPublicController extends Controller
{
    //
    
    public function edit(Request $request, Assignment $assignment)
    {
        abort_if(!$request->hasValidSignature(), 401);

        $routeActionString = "tenant:admin.assignments.";

        $programs = null;
        if ($assignment->isSignableDocument()) {
            $programs = Program::whereIn('id', $assignment->signableDocument->program_ids)->get();
        }
        return view('tenant.assignments.public_edit', compact('assignment', 'programs', 'routeActionString')); 
    }
    
    public function complete(Assignment $assignment)
    {
        $assignment->complete();
        return back()->withSuccess('Marked complete!');
    }

    public function upload(StorePublicFile $request, Assignment $assignment)
    {
        $validated = $request->validated();

        if ($request->hasFile('files')) {
            foreach($validated['files'] as $document) {
                $path = Storage::putFile(File::getAdminStoragePath(), $document);
                $file = File::make([
                    'path' => $path,
                    'filename' => $document->getClientOriginalName()
                ]);
                $file->organization_id = $assignment->assigned_to_organization_id;
                $file->entity_type = Assignment::class;
                $file->entity_id = $assignment->id;
                $file->save();
            }
        }
        return back()->withSuccess('Files uploaded.');
    }

    public function download($file)
    {
        $file = File::withoutGlobalScopes()->find($file);
        return Storage::download($file->path, $file->filename);
    }
}
