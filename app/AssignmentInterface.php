<?php

namespace App;

interface AssignmentInterface
{
    //
    public function complete(User $user);
    public function approve(User $user);
    public function isPending();
    public function isApproved();
    public function files(Organization $organization = null);
}
