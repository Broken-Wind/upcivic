<?php

namespace App\Http\Controllers;

use App\Program;
use Illuminate\Http\Request;
use App\Template;
use App\Site;
use App\Organization;
use App\Contributor;
use App\Http\Requests\StoreProgram;
use App\Meeting;
use App\Http\Requests\UpdateProgram;

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

        return view('tenant.admin.programs.index', compact('programs'));
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

        $organizations = Organization::where('id', '!=', tenant()['id'])->orderBy('name')->get();

        return view('tenant.admin.programs.create', compact('templates', 'sites', 'organizations'));

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

        DB::transaction(function () use ($validated) {

            foreach ($validated['start_dates'] as $key => $start_date) {

                if ($start_date && isset($validated['start_times'][$key])) {

                    $template = Template::find($validated['templates'][$key]);

                    $program = Program::create([

                        'name' => $template['name'],

                        'description' => $template['description'],

                        'ages_type' => $validated['ages_types'][$key] ?? $template['ages_type'],

                        'min_age' => $validated['min_ages'][$key] ?? $template['min_age'],

                        'max_age' => $validated['max_ages'][$key] ?? $template['max_age'],

                    ]);

                    $proposer = new Contributor([

                        'internal_name' => $template['internal_name'],

                        'invoice_amount' => $template['invoice_amount'],

                        'invoice_type' => $template['invoice_type'],

                    ]);

                    $proposer['program_id'] = $program['id'];

                    $proposer['organization_id'] = tenant()->id;

                    $proposer->save();


                    $contributor = new Contributor([]);

                    $contributor['program_id'] = $program['id'];

                    $contributor['organization_id'] = $validated['organization_id'];

                    if ($contributor['organization_id'] != $proposer['organization_id']) {

                        $contributor->save();

                    }


                    $startTime = $validated['start_times'][$key];

                    $endTime = $validated['end_times'][$key] ?? date('H:i:s', strtotime($validated['start_times'][$key] . " +" . $template['meeting_minutes'] . " minutes"));

                    $currentStartDatetime = date('Y-m-d H:i:s', strtotime($validated['start_dates'][$key] . " " . $startTime));

                    $currentEndDatetime = date('Y-m-d H:i:s', strtotime($validated['start_dates'][$key] . " " . $endTime));

                    if (!empty($validated['end_dates'][$key])) {

                        $lastStartDatetime = date('Y-m-d H:i:s', strtotime($validated['end_dates'][$key] . " " . $startTime));

                    } else {

                        $lastStartDatetime = date('Y-m-d H:i:s', strtotime(\Carbon\Carbon::parse($validated['start_dates'][$key])->addDays($template['meeting_count'] * $template['meeting_interval'])));

                    }

                    for ($currentStartDatetime; $currentStartDatetime <= $lastStartDatetime; ($currentStartDatetime = date('Y-m-d H:i:s', strtotime($currentStartDatetime . " +" . $template['meeting_interval'] . "days")))) {

                        $meeting = new Meeting([

                            'start_datetime' => $currentStartDatetime,

                            'end_datetime' => $currentEndDatetime

                        ]);

                        $meeting['program_id'] = $program['id'];

                        $meeting['site_id'] = $validated['site_id'];

                        $meeting->save();


                        $currentEndDatetime = date('Y-m-d H:i:s', strtotime($currentEndDatetime . " +" . $template['meeting_interval'] . " days"));

                    }

                }

            }

        });

        return redirect()->route('tenant:admin.programs.index', \Auth::user()->organizations()->first()->slug)->withSuccess('Program added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Program  $program
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
     * @param  App\Http\Requests\UpdateProgram;  $request
     * @param  \App\Program  $program
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
            'ages_type' => $validated['ages_type'],
            'min_age' => $validated['min_age'],
            'max_age' => $validated['max_age'],

        ]);

        return back()->withSuccess('Program updated successfully.');
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
    }
}
