<?php

namespace App;

use App\Http\Concerns\OwnedByTenant;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    //

    protected $guarded = [];

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

    public function getCategoryAttribute()
    {
        return $this->categories->first() ?? new Category([
            'name' => 'Miscellaneous'
        ]);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
