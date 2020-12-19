<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Facades\Purify;

class SignableDocument extends Model
{
    //
    protected $fillable = [
        'title',
        'content'
    ];
    public function getContentAttribute()
    {
        return Purify::clean($this->attributes['content']);
    }
}
