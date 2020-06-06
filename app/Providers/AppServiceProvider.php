<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\ICategoryRepository;
use App\Repositories\IUserRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Schema\Builder;
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
        $this->app->bind(IUserRepository::class,UserRepository::class);
        $this->app->bind(ICategoryRepository::class,CategoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Builder::defaultStringLength(191);
    }
}
