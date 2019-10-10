<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Upcivic\Organization;
use Faker\Generator as Faker;
use Upcivic\Person;

$factory->define(Organization::class, function (Faker $faker) {
    return [
        //
        'name' => $faker->company,

    ];
});


$factory->afterCreatingState(Organization::class, 'hasAdministrator', function (Organization $organization, Faker $faker) {

    $person = factory(Person::class)->create();

    $organization->administrators()->save($person, ['title' => $faker->title]);

});
