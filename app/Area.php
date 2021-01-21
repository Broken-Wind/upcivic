<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    //
    protected $fillable = [
        'name'
    ];
    public static function defaultArea()
    {
        return self::make([
            'name' => 'Other/Unspecified Area'
        ]);
    }
}
