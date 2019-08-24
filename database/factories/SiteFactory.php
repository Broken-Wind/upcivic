<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Upcivic\Site;
use Faker\Generator as Faker;

$factory->define(Site::class, function (Faker $faker) {
    return [
        //
        'name' => $faker->company,

        'address' => $faker->address,

    ];
});
