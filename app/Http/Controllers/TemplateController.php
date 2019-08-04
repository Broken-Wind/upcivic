<?php

namespace Upcivic\Http\Controllers;

use Upcivic\Template;
use Illuminate\Http\Request;
use Upcivic\Http\Requests\StoreTemplate;
use Upcivic\Http\Requests\UpdateTemplate;

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
            'min_age' => $validated['min_age'],
            'max_age' => $validated['max_age'],
            'ages_type' => $validated['ages_type'],
            'invoice_amount' => 100 * $validated['invoice_amount'],
            'invoice_type' => $validated['invoice_type'],
            'meeting_minutes' => $validated['meeting_minutes'],
            'meeting_interval' => $validated['meeting_interval'],
            'meeting_count' => $validated['meeting_count'],

        ]);

        $template->organization_id = tenant()->id;

        $template->save();

        return redirect()->route('tenant:admin.templates.index', tenant()->slug)->withSuccess('Template added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Upcivic\Template  $template
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
     * @param  \Upcivic\Template  $template
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
            'min_age' => $validated['min_age'],
            'max_age' => $validated['max_age'],
            'ages_type' => $validated['ages_type'],
            'invoice_amount' => 100 * $validated['invoice_amount'],
            'invoice_type' => $validated['invoice_type'],
            'meeting_minutes' => $validated['meeting_minutes'],
            'meeting_interval' => $validated['meeting_interval'],
            'meeting_count' => $validated['meeting_count'],

        ]);

        return back()->withSuccess('Template updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Upcivic\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template)
    {
        //
    }
}
