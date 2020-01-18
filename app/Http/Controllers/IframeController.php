<?php

namespace Upcivic\Http\Controllers;

use Upcivic\Filters\ProgramFilters;
use Upcivic\Program;

class IframeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProgramFilters $programFilters)
    {
        //
        $programs = Program::with(['meetings.site'])->published()->filter($programFilters)->get()->sortBy('start_datetime');
        return view('tenant.iframe.index', compact('programs'));
    }
}
