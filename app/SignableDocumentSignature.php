<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SignableDocumentSignature extends Model
{
    //
    protected $fillable = [
        'organization_id',
        'signature',
        'ip'
    ];
}
