<?php


namespace Tohidplus\Paravel;

use Tohidplus\Paravel\Console\Commands\Executor;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\ServiceProvider;

class ParavelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            Executor::class,
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/config/paravel.php', 'paravel'
        );

        $this->publishes([
            __DIR__ . '/config/paravel.php' => base_path('config/paravel.php')
        ]);

        $config = config('paravel');

        $this->app->bind(Processor::class, function () use ($config) {
            return new Processor(Redis::connection($config['redis_connection']), $config);
        });
        $this->app->when(Executor::class)
            ->needs(Connection::class)
            ->give(function () use ($config) {
                return Redis::connection($config['redis_connection']);
            });
    }
}
