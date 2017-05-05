<?php

namespace Koyeo\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Koyeo\Modules\Contracts\RepositoryInterface;
use Koyeo\Modules\Repository;

class ContractsServiceProvider extends ServiceProvider
{
    /**
     * Register some binding.
     */
    public function register()
    {
        $this->app->bind(RepositoryInterface::class, Repository::class);
    }
}
