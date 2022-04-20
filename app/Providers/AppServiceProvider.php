<?php

namespace App\Providers;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->injectedClasses();
    }

    private function injectedClasses()
    {
        $this->app->singleton(UserRepositoryInterface::class, EloquentUserRepository::class);
        //$this->app->singleton(UserRepositoryInterface::class, EloquentUserRepository::class);
        //$this->app->singleton(UserRepositoryInterface::class, EloquentUserRepository::class);
        //$this->app->singleton(UserRepositoryInterface::class, EloquentUserRepository::class);
    }
}
