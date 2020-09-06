<?php


namespace Tohidplus\Paravel\Console\Commands;

use Tohidplus\Paravel\Process;
use Illuminate\Console\Command;
use Tohidplus\Paravel\Serializer;

class Executor extends Command
{
    protected $signature = "parallel:run {process}";
    protected $description = "Runs parallel processes";
    /**
     * @var Serializer
     */
    private Serializer $serializer;

    /**
     * Executor constructor.
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        parent::__construct();
        $this->serializer = $serializer;
    }

    public function handle()
    {
        /** @var Process $process */
        $process = $this->serializer->unserialize($this->argument('process'));
        echo $this->serializer->serialize($process->handle());
    }
}
