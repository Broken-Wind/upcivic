<?php

namespace App\Http\Controllers;

use App\Contributor;
use App\County;
use App\Filters\ProgramFilters;
use App\Http\Requests\ApproveProgram;
use App\Http\Requests\RejectProgram;
use App\Http\Requests\StoreProgram;
use App\Http\Requests\UpdateProgram;
use App\Mail\ProgramRejected;
use App\Mail\ProgramApproved;
use App\Mail\ProposalSent;
use App\Organization;
use App\Person;
use App\Program;
use App\Site;
use App\Template;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mixpanel;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProgramFilters $programFilters)
    {
        //
        $programs = Program::with(['meetings.site', 'contributors.organization'])->filter($programFilters)->get()->sortBy('start_datetime');
        $programGroups = Program::groupPrograms($programs);
        $programsExist = Program::get()->count() > 0;
        $organizations = Organization::orderBy('name')->get();
        $sites = Site::orderBy('name')->get();
        $templateCount = Template::count();

        return view('tenant.admin.programs.index', compact('programGroups', 'programsExist', 'templateCount', 'organizations', 'sites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $templates = Template::all()->sortBy('internal_name');
        $sites = Site::all()->sortBy('name');
        $organizations = Organization::emailable()->where('id', '!=', tenant()['organization_id'])->orderBy('name')->get();
        $counties = County::orderBy('name')->get();

        return view('tenant.admin.programs.create', compact('templates', 'sites', 'organizations', 'counties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreProgram;  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProgram $request)
    {
        //
        $validated = $request->validated();
        $program = null;
        DB::transaction(function () use ($validated, &$program) {
                $program = Program::fromTemplate($validated);
        });

        return redirect()->route('tenant:admin.programs.edit', [tenant()['slug'], $program])->with('newly_created', true);
    }

    /**
     * Send a Proposal and set Program status to Sent
     *
     * @param UpdateProgram $request
     * @param Program $program
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request, Program $program) {
        $sendingOrganization = tenant()->organization;
        $recipientOrganizations = $program->contributors()->where('organization_id', '!=', $sendingOrganization->id)->get()->map(function ($contributor) {
            return $contributor->organization;
        });
        $proposal = collect([
            'sender' => Auth::user(),
            'sending_organization' => $sendingOrganization,
            'recipient_organizations' => $recipientOrganizations,
            'programs' => [$program],
        ]);

        $program->proposed_at = Carbon::now();

        Auth::user()->approveProgram($program);

        $program->save();

        // TODO: Set status to Sent and disable editing from the contributors. If status sent, block sending again

        \Mail::send(new ProposalSent($proposal));

        return back()->withSuccess('Proposal sent successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function edit(Program $program, $newlyCreated = false)
    {
        abort_if(tenant()->organization_id != $program->proposing_organization_id, 401);

        $organizations = Organization::whereNotIn('id', $program->contributors->pluck('organization_id'))->orderBy('name')->get();
        $sites = Site::orderBy('name')->get();
        $newlyCreated = Session::get('newly_created');

        return view('tenant.admin.programs.edit', compact('program', 'organizations', 'sites', 'newlyCreated'));
    }

    public function show(Program $program)
    {
        $organizations = Organization::whereNotIn('id', $program->contributors->pluck('organization_id'))->orderBy('name')->get();
        $sites = Site::orderBy('name')->get();

        return view('tenant.admin.programs.show', compact('program', 'organizations', 'sites'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UpdateProgram;  $request
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProgram $request, Program $program)
    {
        //
        abort_if(tenant()->organization_id != $program->proposing_organization_id, 401);

        $validated = $request->validated();
        $program->update([
            'name' => $validated['name'],
            'internal_name' => $validated['internal_name'],
            'description' => $validated['description'],
            'public_notes' => $validated['public_notes'],
            'contributor_notes' => $validated['contributor_notes'],
            'ages_type' => $validated['ages_type'],
            'min_age' => $validated['min_age'],
            'max_age' => $validated['max_age'],
            'min_enrollments' => $validated['min_enrollments'],
            'max_enrollments' => $validated['max_enrollments'],
        ]);

        return back()->withSuccess('Program updated successfully.');
    }

    public function reject(RejectProgram $request)
    {
        $validated = $request->validated();
        $program = Program::findOrFail($validated['reject_program_id']);
        $reason = $validated['rejection_reason'];
        \Mail::send(new ProgramRejected($program, Auth::user(), $reason));
        $program->delete();
        return back()->withSuccess('Program rejected.');
    }

    public function approve(ApproveProgram $request)
    {
        $validated = $request->validated();
        $program = Program::findOrFail($validated['approve_program_id']);

        if ($validated['contributor_id'] == 'approve_all') {
            $contributors = $program->contributors->whereNull('approved_at');
        } else {
            $contributors = $program->contributors->where('id', $validated['contributor_id']);
        }
        foreach($contributors as $contributor) {
            Auth::user()->approveProgramForContributor($program, $contributor);
        }

        $proposalNextSteps = $validated['proposal_next_steps'];

        \Mail::send(new ProgramApproved($program, Auth::user(), tenant()->organization, $contributors, $proposalNextSteps));

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        //
        $program->delete();

        return redirect()->route('tenant:admin.programs.index', tenant()['slug'])->withSuccess('Program has been deleted.');
    }
}
