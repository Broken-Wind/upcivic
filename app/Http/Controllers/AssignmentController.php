<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Http\Controllers\Controller;
use App\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    //
    public function pdf(Assignment $assignment)
    {
        $programs = Program::with(['meetings.site', 'contributors.organization'])->whereIn('id', $assignment->metadata['program_ids'])->get();

        $pdf = App::make('dompdf.wrapper');
        $content = view('tenant.assignments.pdf', compact('assignment', 'programs'));
        $pdf->loadHTML($content->render());

        return $pdf->stream();
    }
    public function sign(Request $request, Assignment $assignment)
    {
        abort_if(!$request->hasValidSignature(), 401);
        $programs = Program::whereIn('id', $assignment->metadata['program_ids'])->get();
        $routeActionString = "tenant:admin.assignments.";
        return view('tenant.assignments.sign', compact('assignment', 'programs', 'routeActionString'));
    }
    public function complete(Assignment $assignment)
    {
        $assignment->complete(Auth::user());
        return back()->withSuccess('Marked complete!');
    }
    public function approve(Assignment $assignment)
    {
        $assignment->approve(Auth::user());
        return back()->withSuccess('Marked complete!');
    }

    public function edit(Assignment $assignment)
    {
        $isOutgoingFromTenant = $assignment->assigned_by_organization_id == tenant()->organization_id;
        $routeActionString = 'tenant:admin.assignments.';
        $programs = [];
        if (isset($assignment->metadata['program_ids'])) {
            $programs = Program::whereIn('id', $assignment->metadata['program_ids'])->get();
        }
        return view('tenant.admin.assignments.edit', compact('assignment', 'isOutgoingFromTenant', 'routeActionString', 'programs'));
    }

}
