<?php

function tenant()
{
    return resolve('Upcivic\Tenant');
}

function mixpanel()
{
    return Mixpanel::getInstance(config('mixpanel.token'));
}
