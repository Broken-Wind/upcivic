<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Upcivic\Organization;
use Faker\Generator as Faker;
use Upcivic\Administrator;
use Upcivic\Person;
use Upcivic\User;

$factory->define(Organization::class, function (Faker $faker) {
    return [
        //
        'name' => $faker->company,

    ];
});


$factory->afterCreatingState(Organization::class, 'hasAdministrator', function (Organization $organization, Faker $faker) {

    $person = factory(Person::class)->create();

    $administrator = new Administrator(['title' => $faker->title]);

    $administrator['person_id'] = $person['id'];

    $administrator['organization_id'] = $organization['id'];

    $administrator->save();

});
