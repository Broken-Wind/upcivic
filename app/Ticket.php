<?php

namespace App;

use App\Facades\TicketCode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $guarded = [];

    public function scopeAvailable($query)
    {
        return $query->whereNull('order_id')->whereNull('reserved_at');
    }
    public function scopeAnonymous($query)
    {
        return $query->where('order_id', 0);
    }

    public function scopeUnavailable($query)
    {
        return $query->whereNotNull('order_id')->orWhereNotNull('reserved_at');
    }

    public function release()
    {
        $this->update(['reserved_at' => null]);
    }

    public function claimFor($order)
    {
        $this->code = TicketCode::generateFor($this);
        $order->tickets()->save($this);
    }

    public function reserve()
    {
        $this->update(['reserved_at' => Carbon::now()]);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function getPriceAttribute()
    {
        return $this->program->price;
    }
}
