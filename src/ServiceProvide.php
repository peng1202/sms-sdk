<?php

namespace Mrgoon\AliSms;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config.php' => config_path('alisms.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/Config.php', 'alisms');
        $this->app->bind(AliSms::class, function() {
            return new AliSms();
        });
    }

    protected function configPath()
    {
        return __DIR__ . '/Config.php';
    }
}
