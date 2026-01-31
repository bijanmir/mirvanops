<?php

namespace App\Policies;

use App\Models\Lease;
use App\Models\User;

class LeasePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Lease $lease): bool
    {
        return $user->company_id === $lease->company_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Lease $lease): bool
    {
        return $user->company_id === $lease->company_id;
    }

    public function delete(User $user, Lease $lease): bool
    {
        return $user->company_id === $lease->company_id;
    }
}
