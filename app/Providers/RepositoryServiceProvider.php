<?php

namespace App\Providers;

use App\Interfaces\BattleRepositoryInterface;
use App\Interfaces\MonsterRepositoryInterface;
use App\Repositories\BattleRepository;
use App\Repositories\MonsterRepository;
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
        $this->app->bind(MonsterRepositoryInterface::class, MonsterRepository::class);
        $this->app->bind(BattleRepositoryInterface::class, BattleRepository::class);
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
