<?php

namespace Laravel\Ui;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class UiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the package services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AuthCommand::class,
                UiCommand::class,
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            AuthCommand::class,
            UiCommand::class,
        ];
    }
}
