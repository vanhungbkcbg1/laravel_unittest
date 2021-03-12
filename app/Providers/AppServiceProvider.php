<?php

namespace App\Providers;

use App\Models\NewSymbol;
use App\Repositories\CategoryRepository;
use App\Repositories\ICategoryRepository;
use App\Repositories\INewSymbolRepository;
use App\Repositories\IUserRepository;
use App\Repositories\NewSymbolRepository;
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
        $this->app->bind(INewSymbolRepository::class,NewSymbolRepository::class);
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
