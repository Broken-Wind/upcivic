<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInstructor;
use App\Instructor;
use App\Person;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    //
    public function store(StoreInstructor $request)
    {
        $validated = $request->validated();
        $person = Person::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);
        $instructor = Instructor::make();
        $instructor->person_id = $person->id;
        $instructor->organization_id = tenant()->organization_id;
        $instructor = tenant()->organization->instructors()->save($instructor);
        if ($validated['assign_to_organization_ids']) {
            foreach($validated['assign_to_organization_ids'] as $organizationId) {
                $instructor->assignToOrganization($organizationId);
            }
        }
        return back()->withSuccess('Instructor added.');
    }
}
