<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use Upcivic\Person;

$factory->define(Person::class, function (Faker $faker) {
    return [
        //
        'first_name' => $faker->firstName,

        'last_name' => $faker->lastName,

        'email' => $faker->email,

    ];
});
