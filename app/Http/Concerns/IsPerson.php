<?php

namespace App\Http\Concerns;
trait IsPerson
{
    public function getNameAttribute()
    {
        return $this->person->name;
    }
    public function getFirstNameAttribute()
    {
        return $this->person->first_name;
    }
    public function getLastNameAttribute()
    {
        return $this->person->last_name;
    }
    public function getEmailAttribute()
    {
        return $this->person->email;
    }
    public function getPhoneAttribute()
    {
        return $this->person->phone;
    }
    public function setFirstNameAttribute($value)
    {
        $this->person->first_name = $value;
    }
    public function setLastNameAttribute($value)
    {
        $this->person->last_name = $value;
    }
    public function setEmailAttribute($value)
    {
        $this->person->email = $value;
    }
    public function setPhoneAttribute($value)
    {
        $this->person->phone = $value;
    }
    public function getInitialsAttribute()
    {
        return $this->person->initials;
    }
    public function save(array $options = [])
    {
        $this->person->save($options);
        parent::save($options);
    }
    public function update(array $attributes = [], array $options = [])
    {
        $this->person->update($attributes, $options);
        $personAttributes = collect($this->person->personAttributes);
        $filtered = collect($attributes)->reject(function ($value, $attribute) use ($personAttributes) {
            return $personAttributes->has($attribute);
        });
        parent::update($filtered->toArray(), $options);
    }
    public function delete()
    {
        $this->person->delete();
        parent::delete();
    }
}
