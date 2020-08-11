<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
    protected $fillable = [
        'name',
        'capacity',
        'notes',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
