<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Organization;
use App\Person;
use Faker\Generator as Faker;

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
