<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    public function programs()
    {
        return $this->morphedByMany(Program::class, 'taggable');
    }
    public function templates()
    {
        return $this->morphedByMany(Template::class, 'taggable');
    }
}
