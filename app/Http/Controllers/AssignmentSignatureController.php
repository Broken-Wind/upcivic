<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssignmentSignature;
use App\Mail\DocumentComplete;
use Illuminate\Http\Request;

class AssignmentSignatureController extends Controller
{
    //
    public function store(StoreAssignmentSignature $request, Assignment $assignment)
    {
        $validated = $request->validated();
        if ($assignment->assignedToOrganization->id == $validated['organization_id']) {
            $assignment->complete();
        }
        $assignment->signableDocument->signatures()->create([
                'organization_id' => $validated['organization_id'],
                'signature' => $validated['signature'],
                'ip' => $request->ip()
        ]);
        if ($assignment->isFullySigned()) {
            \Mail::send(new DocumentComplete($assignment));
        }
        return back()->withSuccess('Signature applied.');
    }
}
