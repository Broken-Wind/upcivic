<?php

namespace Upcivic\Http\Controllers;

use Illuminate\Http\Request;
use Upcivic\Http\Requests\UpdateProgramContributors;
use Upcivic\Contributor;
use Upcivic\Program;

class ProgramContributorController extends Controller
{
    //
    public function update(UpdateProgramContributors $request, Program $program)
    {

        $validated = $request->validated();

        foreach($validated['contributors'] as $id => $contributor){

            $updatingContributor = Contributor::find($id);

            $updatingContributor->update([

                'invoice_type' => $contributor['invoice_type'],

                'invoice_amount' => $contributor['invoice_amount'] !== null ? $contributor['invoice_amount'] * 100 : null,

            ]);

        }

        if ($validated['newContributor']['organization_id'] != null) {

            $newContributor = new Contributor([

                'invoice_type' => $validated['newContributor']['invoice_type'],

                'invoice_amount' => $validated['newContributor']['invoice_amount'] ? $validated['newContributor']['invoice_amount'] * 100 : null,

            ]);

            $newContributor['organization_id'] = $validated['newContributor']['organization_id'];

            $program->contributors()->save($newContributor);

        }

        return back()->withSuccess('Contributors updated successfully.');

    }

    public function destroy(Program $program, Contributor $contributor)
    {

        if ($program->contributors->count() < 2) {

            return back()->withErrors(['error' => 'You cannot remove the last contributor from a program. You may delete the program instead.']);

        }

        $contributor->delete();

        return back()->withSuccess('Contributor removed successfully.');

    }
}
