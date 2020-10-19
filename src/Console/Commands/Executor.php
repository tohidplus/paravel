<?php


namespace Tohidplus\Paravel\Console\Commands;

use Tohidplus\Paravel\Facades\Serializer;
use Tohidplus\Paravel\ProcessHandler;
use Illuminate\Console\Command;

class Executor extends Command
{
    protected $signature = "parallel:run {process}";
    protected $description = "Runs parallel processes";


    public function handle()
    {
        /** @var ProcessHandler $process */
        $process = Serializer::base64_unserialize($this->argument('process'));
        echo Serializer::base64_serialize($process->handle());
    }
}
