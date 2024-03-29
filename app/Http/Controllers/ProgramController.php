<?php

namespace App\Http\Controllers;

use App\Contributor;
use App\County;
use App\Exceptions\CannotManuallyUpdateInternalRegistrationsException;
use App\Filters\ProgramFilters;
use App\Http\Requests\ApproveProgram;
use App\Http\Requests\RejectProgram;
use App\Http\Requests\BulkActionPrograms;
use App\Http\Requests\StoreProgram;
use App\Http\Requests\UpdateProgram;
use App\Mail\ProgramRejected;
use App\Mail\ProgramCanceledContributors;
use App\Mail\ProgramApproved;
use App\Mail\ProposalSent;
use App\Organization;
use App\Person;
use App\Program;
use App\Site;
use App\Template;
use Carbon\Carbon;
use App\Exports\ProgramsExport;
use App\Http\Requests\DestroyProgram;
use App\Http\Requests\UpdateProgramRoster;
use App\Http\Requests\UpdateRegistrationOptions;
use App\Instructor;
use App\Mail\PriceChange;
use App\Mail\ProgramCanceledParticipants;
use App\Task;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mixpanel;
use Maatwebsite\Excel\Facades\Excel;

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
        $programs = Program::with(['meetings.site', 'meetings.instructors', 'contributors.organization', 'tickets'])->filter($programFilters)->get()->sortBy('start_datetime');
        $programGroups = Program::groupPrograms($programs);
        $programsExist = Program::get()->count() > 0;
        $groupsIncludeArea = tenant()->organization->hasAreas();
        $organizations = Organization::orderBy('name')->get();
        $sites = Site::orderBy('name')->get();
        $instructors = Instructor::all()->sortBy('person.first_name');
        $templateCount = Template::count();
        $tasks = Task::orderBy('name')->get();

        return view('tenant.admin.programs.index', compact('programGroups', 'programsExist', 'groupsIncludeArea', 'instructors', 'templateCount', 'organizations', 'sites', 'tasks'));
    }

    public function bulkAction(BulkActionPrograms $request)
    {
        $validated = $request->validated();

        if ($validated['action'] == 'export') {
            $today = Carbon::now()->toDateString();
            return Excel::download(new ProgramsExport($validated['program_ids']), 'Programs-' . $today . '.xlsx');
        }
        return back()->withErrors('Error: No action was selected.');
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
        $areas = tenant()->organization->areas()->orderBy('name')->get();
        $organizations = Organization::emailable()->where('id', '!=', tenant()['organization_id'])->orderBy('name')->get();

        return view('tenant.admin.programs.create', compact('templates', 'sites', 'organizations', 'areas'));
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
     * @param Program $program
     * @return \Illuminate\Http\Response
     */
    public function send(Program $program) {
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
     * Set Program status to Sent without sending Proposal Sent email
     *
     * @param Program $program
     * @return \Illuminate\Http\Response
     */
    public function markSent(Program $program) {

        $program->proposed_at = Carbon::now();
        Auth::user()->approveProgram($program);
        $program->save();
        return back()->withSuccess('Proposal sent successfully.');
    }

    public function proposalPreview(Program $program) {
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

        $this->proposed_at = Carbon::now();

        return (new ProposalSent($proposal))->render();
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
    public function getJson(Request $request) {
        try {
            $program = Program::findOrFail($request['program_id']);
        } catch (Exception $e) {
            return json_encode($e);
        }
        return json_encode([
            'id' => $program->id,
            'name' => $program->name,
            'site' => $program->site->name,
            'description_of_meetings' => $program->description_of_meetings,
            'start_time' => $program->start_time,
            'end_time' => $program->end_time,
            'contributors' => $program->contributors->pluck('name')->implode(', '),
            'meetings' => $program->meetings->map(function ($meeting) {
                return [
                    'id' => $meeting->id,
                    'start_date' => $meeting->start_date,
                    'end_date' => $meeting->end_date,
                    'start_time' => $meeting->start_time,
                    'end_time' => $meeting->end_time,
                    'site' => $meeting->site->name,
                    'instructor_list' => empty($meeting->instructor_list) ? 'No instructors!' : $meeting->instructor_list,
                ];
            })
        ]);
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
        ]);

        return back()->withSuccess('Program updated successfully.');
    }

    public function updateRegistrationOptions(UpdateRegistrationOptions $request, Program $program)
    {
        $validated = $request->validated();
        if ($program->canUpdateEnrollmentsBy(tenant())) {
            if (
                $program->isProposalSent()
                && $program->hasOtherContributors()
                && isset($validated['price'])
                && $program->formatted_price != $validated['price']
                ) {
                \Mail::send(new PriceChange($program, $validated['price'], tenant(), Auth::user()));
            }
            $program->price = isset($validated['price']) ? $validated['price'] * 100 : null;
            $program->min_enrollments = $validated['min_enrollments'];
            // If a program allows registration via Upcivic, we should not allow manual updating of the current enrollments.
            if ($program->getContributorFor(tenant())->acceptsRegistrations()) {
                $program->setMaxEnrollments($validated['max_enrollments'] ?? $program->max_enrollments);
            } else {
                $program->updateEnrollments($validated['enrollments'] ?? $program->enrollments, $validated['max_enrollments'] ?? $program->max_enrollments);
            }
            $program->save();
        }
        $contributor = $program->getContributorFor(tenant());
        $contributor->update([
            'enrollment_url' => $validated['enrollment_url'] ?? null,
            'enrollment_instructions' => $validated['enrollment_instructions'] ?? null,
            'enrollment_message' => $validated['enrollment_message'] ?? null,
            'internal_registration' => $validated['internal_registration'] ?? null,
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

        $proposalNextSteps = $validated['proposal_next_steps'] ?? '';

        \Mail::send(new ProgramApproved($program, Auth::user(), tenant()->organization, $contributors, $proposalNextSteps));

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyProgram $request, Program $program)
    {
        //
        abort_if(!$program->canBeDeletedBy(tenant()), 401);
        $validated = $request->validated();
        $programId = $program->id;
        if ($program->isProposalSent()) {
            $program->contributors->map(function ($contributor) {
                return $contributor->organization->emailableContacts();
            })->flatten()->unique('email')->each(function ($administrator) use ($program, $validated) {
                \Mail::to($administrator['email'])->send(new ProgramCanceledContributors($program, $validated['cancellation_message'] ?? null, Auth::user()));
            });
        }
        if ($program->hasParticipants()) {
            $program->participants->map(function ($participant)  {
                return $participant->primaryContact()->email;
            })->unique()->each(function ($email) use ($program, $validated) {
                \Mail::to($email)->send(new ProgramCanceledParticipants($program, $validated['cancellation_message'] ?? null, Auth::user(), tenant()->organization));
            });
        }
        $program->delete();
        return redirect('https://dashboard.stripe.com/search?query=program_id%3A' . $programId);

    }

}
