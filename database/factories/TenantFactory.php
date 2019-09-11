<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Upcivic\Tenant;
use Faker\Generator as Faker;
use Upcivic\Organization;
use Upcivic\User;

$factory->define(Tenant::class, function (Faker $faker) {
    return [
        //
        'organization_id' => factory(Organization::class)->create()->id,

        'slug' => $faker->word,

    ];
});



$factory->afterCreatingState(Tenant::class, 'hasTwoUsers', function (Tenant $tenant, Faker $faker) {

    $tenant->users()->saveMany(factory(User::class, 2)->create());

});
