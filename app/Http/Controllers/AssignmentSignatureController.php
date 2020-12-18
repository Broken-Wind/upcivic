<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssignmentSignature;
use Illuminate\Http\Request;

class AssignmentSignatureController extends Controller
{
    //
    public function store(StoreAssignmentSignature $request, Assignment $assignment)
    {
        $validated = $request->validated();
        $signatureKey = 'assigned_to_organization_signature';
        $signature = [
            $signatureKey => [
                'organization_id' => $validated['organization_id'],
                'signature' => $validated['signature'],
                'timestamp' => now()->toRfc7231String(),
                'ip' => $request->ip()
            ]
        ];
        $assignment->sign($signature);
        $assignment->complete();
        return back()->withSuccess('Signature applied.');
    }
}
