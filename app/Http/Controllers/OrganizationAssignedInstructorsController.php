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
            $organization->instructorsAssignedBy(tenant()->organization)->detach();
            /**
            tenant()->organization->instructorsAssignedTo($organization)->each(function ($instructor) {
                $instructor->delete();
            });
            */
        } else {
            $organization->incomingAssignedInstructors()->attach($validated['assignInstructorIds']);
        }
        return back()->withSuccess('Assignments updated!');
    }
}
