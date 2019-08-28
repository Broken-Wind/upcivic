<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Upcivic\Organization;
use Faker\Generator as Faker;
use Upcivic\User;

$factory->define(Organization::class, function (Faker $faker) {
    return [
        //
        'name' => $faker->company,

        'slug' => $faker->word,

    ];
});


$factory->afterCreatingState(Organization::class, 'hasTwoUsers', function (Organization $organization, Faker $faker) {

    $organization->users()->saveMany(factory(User::class, 2)->create());

});
