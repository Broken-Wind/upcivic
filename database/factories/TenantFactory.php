<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Upcivic\Tenant;
use Faker\Generator as Faker;
use Upcivic\Organization;

$factory->define(Tenant::class, function (Faker $faker) {
    return [
        //
        'organization_id' => factory(Organization::class)->create()->id,

        'slug' => $faker->word,

    ];
});
