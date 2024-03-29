<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Organization;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssignmentSignature;
use App\Mail\DocumentComplete;
use App\Mail\DocumentSigned;
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
        } else {
            $organization = Organization::find($validated['organization_id']);
            \Mail::send(new DocumentSigned($assignment, $organization));
        }
        return back()->withSuccess('Signature applied.');
    }
}
