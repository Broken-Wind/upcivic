<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Participant;
use App\Person;

$factory->define(Participant::class, function (Faker $faker) {
    return [
        //
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'needs' => $faker->text,
        'birthday' => $faker->date($format = 'Y-m-d', $max = 'now')
    ];
});

$factory->afterCreatingState(Participant::class, 'withContact', function (Participant $participant) {
    $contact = factory(Person::class)->create();
    $participant->contacts()->attach($contact);
});
