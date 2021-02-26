<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Administrator;
use App\Organization;
use App\Person;
use App\Tenant;
use App\User;
use Faker\Generator as Faker;

$factory->define(Tenant::class, function (Faker $faker) {
    return [
        //
        'organization_id' => factory(Organization::class)->create(['name' => 'Generated by base factory'])->id,
        'slug' => $faker->word,
        'stripe_account_id' => 'test_acct_1234',
        'stripe_access_token' => 'test_access_token_12345',
    ];
});

$factory->afterCreatingState(Tenant::class, 'hasTwoUsers', function (Tenant $tenant, Faker $faker) {
    $tenant->users()->saveMany(factory(User::class, 2)->create());
});

$factory->afterCreatingState(Tenant::class, 'hasTwoAdministrators', function (Tenant $tenant, Faker $faker) {
    for ($i = 0; $i < 2; $i++) {
        $person = factory(Person::class)->create();

        $tenant->organization->administrators()->save($person, ['title' => $faker->title]);
    }
});
