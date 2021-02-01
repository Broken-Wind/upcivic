<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Order;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Order::class, function (Faker $faker) {
    return [
        'amount' => 5250,
        'email' => 'somebody@example.com',
    ];
});