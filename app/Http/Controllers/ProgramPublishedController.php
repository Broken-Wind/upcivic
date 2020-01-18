<?php

namespace Upcivic\Http\Controllers;

use Illuminate\Http\Request;
use Upcivic\Http\Requests\UpdateProgramPublished;
use Upcivic\Program;

class ProgramPublishedController extends Controller
{
    //
    /**
     * Update the specified resource in storage.
     *
     * @param  Upcivic\Http\Requests\UpdateProgram;  $request
     * @param  \Upcivic\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProgramPublished $request, Program $program)
    {
        $validated = $request->validated();
        if (isset($validated['publish_now'])) {
            $program->publish();
            return back()->withSuccess('Program published.');
        }
        if (empty($validated['published_at'])) {
            $program->unpublish();
            return back()->withSuccess('Program unpublished.');
        }
        $program->publish($validated['published_at']);
        return back()->withSuccess('Program scheduled to publish.');
    }

}
