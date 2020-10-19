<?php


namespace Tohidplus\Paravel;

use SuperClosure\Serializer;
use Tohidplus\Paravel\Console\Commands\Executor;
use Illuminate\Support\ServiceProvider;
use Tohidplus\Paravel\Process\ParallelProcessor;
use Tohidplus\Paravel\Serializer\ParavelSerializer;

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

        $this->app->bind('paravel', function () use ($config) {
            return new ParallelProcessor($config);
        });
        $this->app->singleton('paravel-serializer', function () {
            return new ParavelSerializer(new Serializer());
        });
    }
}
