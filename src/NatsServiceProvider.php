<?php
namespace Lauchoit\LaravelNats;

use Illuminate\Support\ServiceProvider;
use Lauchoit\LaravelNats\Commands\NatsSub;
class NatsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/nats.php', 'nats');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                NatsSub::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config/nats.php' => config_path('nats.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../routes/nats.php' => base_path('routes/nats.php'),
        ], 'routes');

//        $this->loadRoutesFrom(__DIR__ . '/../routes/nats.php');
    }
}
