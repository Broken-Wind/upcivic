<?php

namespace App;

class RandomOrderConfirmationNumberGenerator implements OrderConfirmationNumberGenerator
{

    public function generate()
    {

        $pool = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';

        $confirmationNumber = substr(str_shuffle(str_repeat($pool, 24)), 0, 24);

        return $confirmationNumber;

    }

}

