<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Facades\Purify;

class SignableDocumentAssignment extends Model
{
    //
    protected $fillable = [
        'title',
        'content',
        'program_ids'
    ];
    protected $casts = [
        'program_ids' => 'array'
    ];
    public function signatures()
    {
        return $this->hasMany(SignableDocumentSignature::class, 'document_id');
    }
    public function getContentAttribute()
    {
        return Purify::clean($this->attributes['content']);
    }
}
