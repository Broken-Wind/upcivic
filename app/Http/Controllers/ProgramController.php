<?php

namespace Upcivic\Http\Controllers;

use Upcivic\Program;
use Upcivic\Template;
use Upcivic\Site;
use Upcivic\Organization;
use Upcivic\Http\Requests\StoreProgram;
use Upcivic\Http\Requests\UpdateProgram;

use DB;
use Illuminate\Support\Facades\Auth;
use Mixpanel;
use Upcivic\Filters\ProgramFilters;
use Upcivic\Mail\ProposalSent;

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
        $programs = Program::with(['meetings.site', 'contributors'])->filter($programFilters)->get()->sortBy('start_datetime');

        $organizations = Organization::orderBy('name')->get();
        $sites = Site::orderBy('name')->get();

        $templateCount = Template::count();

        return view('tenant.admin.programs.index', compact('programs', 'templateCount', 'organizations', 'sites'));

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

        $organizations = Organization::where('organization_id', '!=', tenant()['id'])->orderBy('name')->get();

        return view('tenant.admin.programs.create', compact('templates', 'sites', 'organizations'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Upcivic\Http\Requests\StoreProgram;  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProgram $request)
    {
        //
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {

            foreach ($validated['programs'] as $key => $program) {

                $program['recipient_organization_id'] = $validated['recipient_organization_id'];

                $program['site_id'] = $validated['site_id'];

                Program::fromTemplate($program);

            }

        });

        $recipientOrganization = Organization::find($validated['recipient_organization_id']);

        $sendingOrganization = tenant();

        $proposal = collect([

            'sender' => Auth::user(),

            'sending_organization' => $sendingOrganization,

            'recipient_organization' => $recipientOrganization,

        ]);

        \Mail::send(new ProposalSent($proposal));

        return redirect()->route('tenant:admin.programs.index', tenant()['slug'])->withSuccess('Program added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Upcivic\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function edit(Program $program)
    {
        //

        $organizations = Organization::whereNotIn('id', $program->contributors->pluck('organization_id'))->orderBy('name')->get();

        $sites = Site::orderBy('name')->get();

        return view('tenant.admin.programs.edit', compact('program', 'organizations', 'sites'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Upcivic\Http\Requests\UpdateProgram;  $request
     * @param  \Upcivic\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProgram $request, Program $program)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Upcivic\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        //

        $program->delete();

        return redirect()->route('tenant:admin.programs.index', tenant()['slug'])->withSuccess('Program has been deleted.');
    }
}
