<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\UserObserver;
use App\Interfaces\ProductInterface;
use App\Interfaces\CategoryInterface;
use App\Interfaces\OrderInterface;
use App\Interfaces\DeliveryBoyInterface;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\DeliveryBoyRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductInterface::class, ProductRepository::class);
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(OrderInterface::class, OrderRepository::class);
        $this->app->bind(DeliveryBoyInterface::class, DeliveryBoyRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
    }
}
