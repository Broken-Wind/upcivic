<?php

namespace Upcivic\Http\Controllers;

use Upcivic\Program;
use Illuminate\Http\Request;
use Upcivic\Template;
use Upcivic\Site;
use Upcivic\Organization;
use Upcivic\Contributor;
use Upcivic\Http\Requests\StoreProgram;
use Upcivic\Meeting;
use Upcivic\Http\Requests\UpdateProgram;

use DB;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $programs = Program::with('meetings')->get()->sortBy('start_datetime');

        $templateCount = Template::count();

        return view('tenant.admin.programs.index', compact('programs', 'templateCount'));
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

        $organizations = Organization::published()->where('id', '!=', tenant()['id'])->orderBy('name')->get();

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

                $program['organization_id'] = $validated['organization_id'];

                $program['site_id'] = $validated['site_id'];

                Program::fromTemplate($program);

            }

        });

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

        $organizations = Organization::published()->whereNotIn('id', $program->contributors->pluck('organization_id'))->orderBy('name')->get();

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
