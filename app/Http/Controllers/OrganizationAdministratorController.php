<?php

namespace App\Http\Controllers;

use App\Administrator;
use App\Http\Requests\StoreAdministrator;
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

        ]);

        $organization->administrators()->save($person, ['title' => $validated['title']]);

        \Mail::send(new ListedAsAdministrator(\Auth::user(), $organization, $person));

        return back()->withSuccess("Added {$person->name} to {$organization->name}!");
    }
}
