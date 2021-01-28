<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Program;
use App\Order;
use App\Exceptions\NotEnoughTicketsException;

class OrderTest extends TestCase
{
    use RefreshDatabase;

}