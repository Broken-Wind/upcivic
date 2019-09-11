<?php

namespace Upcivic\Http\Controllers;

use Illuminate\Http\Request;
use Upcivic\Administrator;
use Upcivic\Http\Requests\StoreAdministrator;
use Upcivic\Organization;
use Upcivic\Person;

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

        ]);


        $administrator = Administrator::make([

            'title' => $validated['title'],

        ]);

        $administrator['organization_id'] = $organization->id;

        $administrator['person_id'] = $person->id;

        $administrator->save();


        return back()->withSuccess("Added {$person->name} to {$organization->name}!");

    }
}
