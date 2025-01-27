<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use App\Repositories\FileRepositoryInterface;
use App\Repositories\FileRepository;

class FileProvider extends ServiceProvider
{   
    public function register()
    {
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
    }
}