<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInstructor;
use App\Http\Requests\UpdateInstructor;
use App\Instructor;
use App\Person;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    //

    public function index()
    {
        //
        $instructors = Instructor::all();
        return view('tenant.admin.instructors.index', compact('instructors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('tenant.admin.instructors.create');
    }

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
        $instructor->save();
        if (!empty($validated['assign_to_organization_id'])) {
            $instructor->assignToOrganization($validated['assign_to_organization_id']);
        }
        return back()->withSuccess('Instructor added.');
    }

    public function edit(Instructor $instructor)
    {
        //
        return view('tenant.admin.instructors.edit', compact('instructor'));
    }

    public function update(UpdateInstructor $request, Instructor $instructor)
    {
        //
        $validated = $request->validated();

        $instructor->update([

            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],

        ]);

        return back()->withSuccess('Instructor updated successfully.');
    }

    public function destroy(Instructor $instructor)
    {
        //

        $instructor->delete();

        return redirect()->route('tenant:admin.instructors.index', tenant()['slug'])->withSuccess('Instructor has been deleted.');
    }

}
