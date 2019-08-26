<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use Upcivic\Template;

$factory->define(Template::class, function (Faker $faker) {
    return [
        //

        'name' => $faker->company,
        'internal_name' => $faker->word,
        'description' => $faker->text,
        'public_notes' => $faker->text,
        'contributor_notes' => $faker->text,
        'min_age' => $faker->numberBetween(1, 5),
        'max_age' => $faker->numberBetween(6, 10),
        'ages_type' => 'ages',
        'invoice_amount' => $faker->numberBetween(1, 10000),
        'invoice_type' => 'per participant',
        'meeting_minutes' => $faker->numberBetween(1, 1000),
        'meeting_interval' => 1,
        'meeting_count' => 5,
        'min_enrollments' => 3,
        'max_enrollments' => 5,

    ];
});
