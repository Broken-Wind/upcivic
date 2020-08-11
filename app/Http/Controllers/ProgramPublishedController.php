<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProgramPublished;
use App\Program;
use Illuminate\Http\Request;

class ProgramPublishedController extends Controller
{
    //

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UpdateProgram;  $request
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProgramPublished $request, Program $program)
    {
        $validated = $request->validated();
        if (isset($validated['publish_now'])) {
            $program->getContributorFromTenant()->publish();

            return back()->withSuccess('Program published.');
        }
        if (empty($validated['published_at'])) {
            $program->getContributorFromTenant()->unpublish();

            return back()->withSuccess('Program unpublished.');
        }
        $program->getContributorFromTenant()->publish($validated['published_at']);

        return back()->withSuccess('Program scheduled to publish.');
    }
}
