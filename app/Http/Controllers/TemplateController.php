<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTemplate;
use App\Http\Requests\UpdateTemplate;
use App\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $templates = Template::all()->sortBy('internal_name');

        return view('tenant.admin.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('tenant.admin.templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTemplate $request)
    {
        //
        $validated = $request->validated();

        $template = new Template([

            'name' => $validated['name'],
            'internal_name' => $validated['internal_name'],
            'description' => $validated['description'],
            'public_notes' => $validated['public_notes'],
            'contributor_notes' => $validated['contributor_notes'],
            'min_age' => $validated['min_age'],
            'max_age' => $validated['max_age'],
            'ages_type' => $validated['ages_type'],
            'invoice_amount' => 100 * $validated['invoice_amount'],
            'invoice_type' => $validated['invoice_type'],
            'meeting_minutes' => $validated['meeting_minutes'],
            'meeting_interval' => $validated['meeting_interval'],
            'meeting_count' => $validated['meeting_count'],
            'min_enrollments' => $validated['min_enrollments'],
            'max_enrollments' => $validated['max_enrollments'],

        ]);

        $template->organization_id = tenant()->organization->id;

        $template->save();

        return redirect()->route('tenant:admin.templates.index', tenant()['slug'])->withSuccess('Program added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function edit(Template $template)
    {
        //

        return view('tenant.admin.templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTemplate $request, Template $template)
    {
        //
        $validated = $request->validated();

        $template->update([

            'name' => $validated['name'],
            'internal_name' => $validated['internal_name'],
            'description' => $validated['description'],
            'public_notes' => $validated['public_notes'],
            'contributor_notes' => $validated['contributor_notes'],
            'min_age' => $validated['min_age'],
            'max_age' => $validated['max_age'],
            'ages_type' => $validated['ages_type'],
            'invoice_amount' => 100 * $validated['invoice_amount'],
            'invoice_type' => $validated['invoice_type'],
            'meeting_minutes' => $validated['meeting_minutes'],
            'meeting_interval' => $validated['meeting_interval'],
            'meeting_count' => $validated['meeting_count'],
            'min_enrollments' => $validated['min_enrollments'],
            'max_enrollments' => $validated['max_enrollments'],

        ]);

        return back()->withSuccess('Program updated successfully.');
    }

    public function destroy(Template $template)
    {
        //

        $template->delete();

        return redirect()->route('tenant:admin.templates.index', tenant()['slug'])->withSuccess('Program has been deleted.');
    }
}
