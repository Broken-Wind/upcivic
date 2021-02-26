<?php

namespace App\Http\Controllers;

use App\Administrator;
use App\Http\Requests\StoreAdministrator;
use App\Http\Requests\UpdateAdministrator;
use App\Mail\ListedAsAdministrator;
use App\Organization;
use App\Person;
use Illuminate\Http\Request;

class OrganizationAdministratorController extends Controller
{
    //
    public function store(StoreAdministrator $request, Organization $organization)
    {
        $validated = $request->validated();

        $person = Person::create([

            'first_name' => $validated['first_name'],

            'last_name' => $validated['last_name'],

            'email' => $validated['email'],

            'phone' => $validated['phone'],

        ]);

        $organization->administrators()->save($person, ['title' => $validated['title']]);

        \Mail::send(new ListedAsAdministrator(\Auth::user(), $organization, $person));

        return back()->withSuccess("Added {$person->name} to {$organization->name}!");
    }

    public function update(UpdateAdministrator $request, Organization $organization, Person $administrator)
    {
        $validated = $request->validated();

        $administrator->update([

            'first_name' => $validated['first_name'],

            'last_name' => $validated['last_name'],

            'email' => $validated['email'],

            'phone' => $request['phone'],

        ]);

        return back()->withSuccess('Administrator updated successfully.');
    }

    public function edit(Organization $organization, Person $administrator)
    {
        return view('tenant.admin.organizations.administrators.edit', compact('organization', 'administrator'));
    }

    public function destroy(Organization $organization, Person $administrator)
    {
        $administrator->delete();

        return redirect()->route('tenant:admin.organizations.edit',[tenant()['slug'], $organization]) ->withSuccess('Administrator has been deleted.');
    }

}
