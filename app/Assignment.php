<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    //
    public function scopeOutgoing($query)
    {
        return $query->where('assigned_by_organization_id', tenant()->organization_id);
    }
    public function scopeIncoming($query)
    {
        return $query->where('assigned_to_organization_id', tenant()->organization_id);
    }
}
