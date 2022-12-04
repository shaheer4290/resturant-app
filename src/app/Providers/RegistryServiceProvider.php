<?php

namespace App\Providers;

use App\Services\OrderService;
use App\Services\OrderServiceImpl;
use Illuminate\Support\ServiceProvider;

class RegistryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OrderService::class, OrderServiceImpl::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
