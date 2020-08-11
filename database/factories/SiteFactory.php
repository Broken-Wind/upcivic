<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use Upcivic\Location;
use Upcivic\Site;

$factory->define(Site::class, function (Faker $faker) {
    return [
        //
        'name' => $faker->company,

        'address' => $faker->address,

    ];
});

$factory->afterCreatingState(Site::class, 'demoRecCenter', function (Site $site, Faker $faker) {
    $locationTemplates = [
        [
            'name' => 'Room 1',
            'capacity' => 15,
            'notes' => 'No wifi, large windows, no overnight storage.',
        ],
        [
            'name' => 'Room 2',
            'capacity' => 30,
            'notes' => 'Wifi, small windows, overnight equipment storage is sometimes possible.',
        ],
        [
            'name' => 'Room 3',
            'capacity' => 30,
            'notes' => 'Wheelchair accessible, wifi, overnight equipment storage is sometimes possible.',
        ],
        [
            'name' => 'Soccer Field 1',
            'capacity' => 60,
            'notes' => '',
        ],
        [
            'name' => 'Soccer Field 2',
            'capacity' => 60,
            'notes' => '',
        ],
        [
            'name' => 'Kitchen',
            'capacity' => 12,
            'notes' => 'Refrigerator, freezer, sink, gas stove, garbage. Vendors must provide cookware.',
        ],
        [
            'name' => 'Auditorium',
            'capacity' => 250,
            'notes' => 'Various seating options available',
        ],
    ];
    foreach ($locationTemplates as $template) {
        $locations[] = factory(Location::class)->create([
            'name' => $template['name'],
            'site_id' => $site['id'],
            'capacity' => $template['capacity'],
            'notes' => $template['notes'],
        ]);
    }
});

$factory->afterCreatingState(Site::class, 'demoCommunityCenter', function (Site $site, Faker $faker) {
    $locationTemplates = [
        [
            'name' => 'Room 1',
            'capacity' => 25,
            'notes' => 'No wifi, large windows, no overnight storage.',
        ],
        [
            'name' => 'Room 2',
            'capacity' => 30,
            'notes' => 'Wifi, small windows, overnight equipment storage is sometimes possible.',
        ],
        [
            'name' => 'Conference Room 1',
            'capacity' => 10,
            'notes' => 'Wheelchair accessible, wifi, overnight equipment storage is sometimes possible.',
        ],
        [
            'name' => 'Conference Room 2',
            'capacity' => 10,
            'notes' => 'Wheelchair accessible, wifi, overnight equipment storage is sometimes possible.',
        ],
        [
            'name' => 'Atrium',
            'capacity' => 30,
            'notes' => 'Members of the public may walk through the atrium-- this may be disruptive for larger groups.',
        ],
        [
            'name' => 'Baseball Diamond',
            'capacity' => 25,
            'notes' => '',
        ],
    ];
    foreach ($locationTemplates as $template) {
        $locations[] = factory(Location::class)->create([
            'name' => $template['name'],
            'site_id' => $site['id'],
            'capacity' => $template['capacity'],
            'notes' => $template['notes'],
        ]);
    }
});
