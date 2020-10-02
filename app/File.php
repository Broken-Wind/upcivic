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
    private function getEntityAttribute()
    {
        return $this->entity_type::withoutGlobalScopes()->find($this->entity_id);
    }
    public function canDownload(User $user)
    {
        return $user->organizations->whereIn('id', $this->accessible_organizations->pluck('id'));
    }
    public function getAccessibleOrganizationsAttribute(){
        return $this->entity->accessible_organizations->push($this->organization)->unique();
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
