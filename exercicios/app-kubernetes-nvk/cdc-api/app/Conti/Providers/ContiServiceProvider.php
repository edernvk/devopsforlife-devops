<?php

namespace Conti\Providers;

use Conti\Resources\FuncionariosResource;
use Illuminate\Support\ServiceProvider;

class ContiServiceProvider extends ServiceProvider
{
    public function boot()
    {
//        FuncionariosResource::withoutWrapping();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\Conti\Interfaces\ContiInterface::class, \Conti\Services\ContiService::class);
    }
}
