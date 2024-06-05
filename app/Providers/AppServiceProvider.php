<?php

namespace App\Providers;

use App\Auth\Auth;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Facades\DB;
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
        $this->app->singleton(Auth::class, fn () => new Auth());

        $this->app->alias(Auth::class, 'spip.auth');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! Type::hasType('enum')) {
            Type::addType('enum', StringType::class);
        }

        DB::getDoctrineConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
}
