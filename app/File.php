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
        return $query->withoutGlobalScope('TenantOwnedFile')->where('entity_type', get_class($entity))->where('entity_id', $entity->id);
    }
    public function getDownloadLinkAttribute()
    {
        return tenant()->route('tenant:admin.files.download', [$this->id]);
    }
    public function canDelete(User $user)
    {
        return $user->organizations->find($this->organization_id);
    }
}
