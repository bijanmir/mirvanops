<?php

namespace App\Observers;

use App\Livewire\Dashboard;

class CacheObserver
{
    public function created($model)
    {
        $this->clearCache($model);
    }

    public function updated($model)
    {
        $this->clearCache($model);
    }

    public function deleted($model)
    {
        $this->clearCache($model);
    }

    protected function clearCache($model)
    {
        if (isset($model->company_id)) {
            Dashboard::clearCache($model->company_id);
        }
    }
}