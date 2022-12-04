<?php

namespace App\Providers;

use App\Repositories\IngredientRepository;
use App\Repositories\IngredientRepositoryImpl;
use App\Repositories\OrderRepository;
use App\Repositories\OrderRepositoryImpl;
use App\Repositories\ProductRepository;
use App\Repositories\ProductRepositoryImpl;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryImpl;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OrderRepository::class, OrderRepositoryImpl::class);
        $this->app->bind(ProductRepository::class, ProductRepositoryImpl::class);
        $this->app->bind(IngredientRepository::class, IngredientRepositoryImpl::class);
        $this->app->bind(UserRepository::class, UserRepositoryImpl::class);
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
