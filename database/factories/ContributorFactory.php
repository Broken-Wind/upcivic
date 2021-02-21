<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Contributor;
use App\Organization;
use App\Program;
use App\Tenant;
use Faker\Generator as Faker;

$factory->define(Contributor::class, function (Faker $faker) {
    $program = factory(Program::class)->create();
    $organization = factory(Organization::class)->create();
    return [
        //
        'program_id' => $program->id,
        'organization_id' => $organization->id,
        'internal_registration' => true,
        'invoice_amount' => 1337,
        'invoice_type' => 'per participant',
    ];
});
$factory->state(Contributor::class, 'hasTenant', function (Faker $faker) {
    $tenant = factory(Tenant::class)->create();
    return [
        'organization_id' => $tenant->organization_id,
    ];
});
