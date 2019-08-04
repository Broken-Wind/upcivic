<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contributor extends Model
{
    //
    protected $fillable = [

        'internal_name',

        'invoice_amount',

        'invoice_type',

    ];

    public function getFormattedInvoiceAmountAttribute() {

        return isset($this->invoice_amount) ? number_format($this->invoice_amount / 100, 2) : null;

    }

    public function program()
    {

        return $this->belongsTo(Program::class);

    }

    public function organization()
    {

        return $this->belongsTo(Organization::class);

    }
}
