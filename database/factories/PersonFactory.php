<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Upcivic\Person;
use Faker\Generator as Faker;

$factory->define(Person::class, function (Faker $faker) {
    return [
        //
        'first_name' => $faker->firstName,

        'last_name' => $faker->lastName,

        'email' => $faker->email,

    ];
});