<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\FileRepositoryInterface;
use App\Repositories\FileRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

