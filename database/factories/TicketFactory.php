<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Contributor;
use App\Order;
use App\Participant;
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

$factory->afterCreatingState(App\Ticket::class, 'withParticipant', function (Ticket $ticket) {
    $participant = factory(Participant::class)->state('withContact')->create();
    $order = factory(Order::class)->create();
    $ticket->order_id = $order->id;
    $ticket->participant_id = $participant->id;
    $ticket->reserved_at = Carbon::now();
    $ticket->code = 'TEST_CODE';
    $ticket->save();
});
