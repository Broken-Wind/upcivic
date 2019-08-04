<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\OwnedByTenant;

class Template extends Model
{
    //
    use OwnedByTenant;

    protected $fillable = [
        'name',
        'internal_name',
        'description',
        'min_age',
        'max_age',
        'ages_type',
        'invoice_amount',
        'invoice_type',
        'meeting_minutes',
        'meeting_interval',
        'meeting_count',
    ];

    public function getInternalNameAttribute()
    {

        return $this->attributes['internal_name'] ?? $this->name;

    }

    public function getFormattedInvoiceAmountAttribute() {

        return number_format($this->invoice_amount / 100, 2);

    }

    public function tenant()
    {

        return $this->belongsTo(Organization::class, 'organization_id');

    }
}
