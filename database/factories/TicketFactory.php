<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Contributor;
use App\Ticket;
use App\Program;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Ticket::class, function (Faker $faker) {
    return [
        'program_id' => function () {
            return factory(App\Program::class)->create()->id;
        },
    ];
});

$factory->state(App\Ticket::class, 'reserved', function ($faker) {
    return [
        'reserved_at' => Carbon::now(),
    ];
});