<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
