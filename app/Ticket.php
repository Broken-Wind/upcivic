<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $guarded = [];

    public function scopeAvailable($query)
    {
        return $query->whereNull('order_id'); //->whereNull('reserved_at');
    }

    public function release()
    {
        $this->update(['order_id' => null]);
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
