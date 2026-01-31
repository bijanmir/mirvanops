<?php

namespace App\Policies;

use App\Models\MaintenanceRequest;
use App\Models\User;

class MaintenanceRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return $user->company_id === $maintenanceRequest->company_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return $user->company_id === $maintenanceRequest->company_id;
    }

    public function delete(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return $user->company_id === $maintenanceRequest->company_id;
    }
}
