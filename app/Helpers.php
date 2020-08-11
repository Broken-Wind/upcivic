<?php

function tenant()
{
    return resolve(\Upcivic\Tenant::class);
}

function mixpanel()
{
    return Mixpanel::getInstance(config('mixpanel.token'));
}
