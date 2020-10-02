<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //
    protected $fillable = [
        'path',
        'filename'
    ];
    public function scopeEntity($query, $entity)
    {
        return $query->where('uploaded_to_entity_type', $entity);
    }
}
