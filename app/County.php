<?php

namespace Upcivic;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    //
    public function sites()
    {
        return $this->hasMany(Site::class);
    }
}
