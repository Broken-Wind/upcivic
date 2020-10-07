<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAssignedInstructors;
use App\Instructor;
use App\Organization;
use Illuminate\Http\Request;

class OrganizationAssignedInstructorsController extends Controller
{
    //
    public function massUpdate(Organization $organization, UpdateAssignedInstructors $request)
    {
        $validated = $request->validated();
        if (empty($validated['assignInstructorIds'])) {
            $organization->incomingAssignedInstructors()->detach();
        } else {
            $organization->incomingAssignedInstructors()->attach($validated['assignInstructorIds']);
        }
        return back()->withSuccess('Instructors updated!');
    }
}
