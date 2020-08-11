<?php

function tenant()
{
    return resolve(\App\Tenant::class);
}

function mixpanel()
{
    return Mixpanel::getInstance(config('mixpanel.token'));
}
