<?php

namespace App\Policies;

use App\Models\Tenant;
use App\Models\User;

class TenantPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Tenant $tenant): bool
    {
        return $user->company_id === $tenant->company_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Tenant $tenant): bool
    {
        return $user->company_id === $tenant->company_id;
    }

    public function delete(User $user, Tenant $tenant): bool
    {
        return $user->company_id === $tenant->company_id;
    }
}
