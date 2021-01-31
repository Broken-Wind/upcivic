<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $guarded = [];

    public function scopeAvailable($query)
    {
        return $query->whereNull('order_id')->whereNull('reserved_at');
    }

    public function release()
    {
        $this->update(['reserved_at' => null]);
    }

    public function reserve()
    {
        $this->update(['reserved_at' => Carbon::now()]);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function getPriceAttribute()
    {
        return $this->program->contributors->first()->invoice_amount;
    }
}