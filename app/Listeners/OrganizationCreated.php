<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Site;
use App\Organization;

class OrganizationCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
        $organization = $event->organization;
        $site = Site::where('name', '[VIRTUAL]')->firstOrFail();

        $organization->sites()->save($site);

        app('log')->info('Assign site ' . $site->name . ' to organization ' . $organization->name);
    }
}
