<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\County;
use App\Location;
use App\Site;
use Faker\Generator as Faker;

$factory->define(Site::class, function (Faker $faker) {
    return [
        //
        'name' => $faker->company,
        'address' => $faker->address,
        'county_id' => County::inRandomOrder()->first()->id ?? null
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

$factory->afterCreatingState(Site::class, 'demoSchool', function (Site $site, Faker $faker) {
    $locationTemplates = [
        [
            'name' => 'Room 1',
            'capacity' => 30,
            'notes' => 'Kindergarten',
        ],
        [
            'name' => 'Room 2',
            'capacity' => 30,
            'notes' => 'Kindergarten',
        ],
        [
            'name' => 'Room 3',
            'capacity' => 30,
            'notes' => 'First Grade',
        ],
        [
            'name' => 'Room 4',
            'capacity' => 30,
            'notes' => 'Second Grade',
        ],
        [
            'name' => 'Room 5',
            'capacity' => 20,
            'notes' => 'Third Grade',
        ],
        [
            'name' => 'Room 6a',
            'capacity' => 25,
            'notes' => 'Fourth Grade',
        ],
        [
            'name' => 'Room 6b',
            'capacity' => 25,
            'notes' => 'Fourth Grade',
        ],
        [
            'name' => 'Library',
            'capacity' => 100,
            'notes' => '',
        ],
        [
            'name' => 'Multi-Purpose Room',
            'capacity' => 150,
            'notes' => '',
        ],
        [
            'name' => 'Portable A',
            'capacity' => 25,
            'notes' => 'Fifth Grade',
        ],
        [
            'name' => 'Portable B',
            'capacity' => 25,
            'notes' => 'Fifth Grade',
        ],
        [
            'name' => 'Compuer Lab',
            'capacity' => 35,
            'notes' => '',
        ],
        [
            'name' => 'Soccer Field 1',
            'capacity' => 200,
            'notes' => '',
        ],
        [
            'name' => 'Soccer Field 2',
            'capacity' => 200,
            'notes' => '',
        ],
        [
            'name' => 'Baseball Diamond',
            'capacity' => 100,
            'notes' => '',
        ],
        [
            'name' => 'Playground',
            'capacity' => 50,
            'notes' => '',
        ],
        [
            'name' => 'Blacktop',
            'capacity' => 25,
            'notes' => 'Fourth Grade',
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
