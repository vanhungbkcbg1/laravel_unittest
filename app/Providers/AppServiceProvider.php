<?php

namespace App\Providers;

use App\Models\NewSymbol;
use App\Repositories\CategoryRepository;
use App\Repositories\DayOffRepository;
use App\Repositories\ICategoryRepository;
use App\Repositories\IDayOffRepository;
use App\Repositories\INewSymbolRepository;
use App\Repositories\IProcessHistoryRepository;
use App\Repositories\ISymbolAnalyzedRepository;
use App\Repositories\ISymbolPriceRepository;
use App\Repositories\IUserRepository;
use App\Repositories\NewSymbolRepository;
use App\Repositories\ProcessHistoryRepository;
use App\Repositories\SymbolAnalyzedRepository;
use App\Repositories\SymbolPriceRepository;
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
        $this->app->bind(IDayOffRepository::class,DayOffRepository::class);
        $this->app->bind(ISymbolPriceRepository::class,SymbolPriceRepository::class);
        $this->app->bind(IProcessHistoryRepository::class,ProcessHistoryRepository::class);
        $this->app->bind(ISymbolAnalyzedRepository::class,SymbolAnalyzedRepository::class);
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
