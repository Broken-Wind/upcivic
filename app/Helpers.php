<?php



function tenant()
{

    return resolve('Upcivic\Organization');

}

function mixpanel()
{

    return Mixpanel::getInstance(config('mixpanel.token'));

}

