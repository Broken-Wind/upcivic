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
            $program->getContributorFor(tenant())->publish();

            return back();
        }
        if (empty($validated['published_at'])) {
            $program->getContributorFor(tenant())->unpublish();

            return back();
        }
        $program->getContributorFor(tenant())->publish($validated['published_at']);

        return back()->withSuccess('Program scheduled to publish.');
    }
}
