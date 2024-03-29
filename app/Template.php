<?php

namespace App;

use App\Http\Concerns\OwnedByTenant;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    //

    protected $fillable = [
        'name',
        'internal_name',
        'description',
        'public_notes',
        'contributor_notes',
        'min_age',
        'max_age',
        'ages_type',
        'invoice_amount',
        'invoice_type',
        'meeting_minutes',
        'meeting_interval',
        'meeting_count',
        'min_enrollments',
        'max_enrollments',
        'enrollment_message',
    ];

    public function getInternalNameAttribute()
    {
        return $this->attributes['internal_name'] ?? $this->name;
    }

    public function getFormattedInvoiceAmountAttribute()
    {
        return isset($this->invoice_amount) ? number_format($this->invoice_amount / 100, 2, '.', '') : null;
    }

    public function tenant()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
