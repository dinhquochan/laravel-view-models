<?php

namespace DinhQuocHan\ViewModels;

use Illuminate\Support\ServiceProvider;

class ViewModelsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            ViewModelMakeCommand::class,
        ]);
    }
}
