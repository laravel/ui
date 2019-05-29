<?php

namespace Laravel\Ui;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class UiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
    }

    /**
     * Boot the package services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the console commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            AuthCommand::class,
            PresetCommand::class,
        ]);
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
            PresetCommand::class,
        ];
    }
}
