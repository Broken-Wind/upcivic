<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewAssignments;
use App\Http\Requests\StoreManyAssignments;
use App\Organization;
use App\Program;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class AssignmentController extends Controller
{
    //
    public function create()
    {
        $tasks = Task::all();
        return view('tenant.admin.assignments.create', compact('tasks'));
    }
    public function review(ReviewAssignments $request)
    {
        $validated = $request->validated();
        $task = Task::find($validated['task_id']);
        $organizations = Organization::find($validated['organization_ids'])->keyBy('id');
        return view('tenant.admin.assignments.review', compact('task', 'organizations'));
    }
    public function storeMany(StoreManyAssignments $request)
    {
        $validated = $request->validated();
        $task = Task::find($validated['task_id']);
        foreach($validated['organization_ids'] as $organizationId) {
			$programIds = null;
			if (!empty($validated['organization_program_ids'])) {
				$programIds = $validated['organization_program_ids'][$organizationId] ?? null;
			}
            $task->assign($organizationId, $programIds);
        }
        return redirect(tenant()->route('tenant:admin.assignments.outgoing.index'))->withSuccess('Tasks were successfully assigned!');
    }
    public function pdf(Request $request, Assignment $assignment)
    {
        abort_if(!$request->hasValidSignature(), 401);
        $programs = Program::whereIn('id', $assignment->signableDocument->program_ids)->get();
        $pdf = App::make('dompdf.wrapper');
        $content = view('tenant.assignments.pdf', compact('assignment', 'programs'));
        $pdf->loadHTML($content->render());

        return $pdf->stream();
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
        if ($assignment->isSignableDocument()) {
            $programs = Program::whereIn('id', $assignment->signableDocument->program_ids)->get();
        } else {
            $programs = collect();
        }
        $pdfUrl = URL::signedRoute('tenant:assignments.pdf', [tenant()->slug, $assignment]);
        return view('tenant.admin.assignments.edit', compact('assignment', 'isOutgoingFromTenant', 'routeActionString', 'programs', 'pdfUrl'));
    }

    public function destroy(Assignment $assignment)
    {
        $isOutgoingFromTenant = $assignment->assignedByOrganization->id == tenant()->organization_id;
        $redirectToOrganization = $isOutgoingFromTenant ? $assignment->assignedToOrganization->id : $assignment->assignedByOrganization->id;
        $assignment->delete();
        if ($isOutgoingFromTenant) {
            return redirect()->route('tenant:admin.assignments.to.organizations.index', [tenant()->slug, $redirectToOrganization]);
        } else {
            return redirect()->route('tenant:admin.assignments.from.organizations.index', [tenant()->slug, $redirectToOrganization]);
        }
    }

}
