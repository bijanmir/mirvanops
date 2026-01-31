<?php

namespace App\Policies;

use App\Models\Vendor;
use App\Models\User;

class VendorPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Vendor $vendor): bool
    {
        return $user->company_id === $vendor->company_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Vendor $vendor): bool
    {
        return $user->company_id === $vendor->company_id;
    }

    public function delete(User $user, Vendor $vendor): bool
    {
        return $user->company_id === $vendor->company_id;
    }
}
