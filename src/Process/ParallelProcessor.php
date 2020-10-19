<?php


namespace Tohidplus\Paravel\Process;

use Closure;
use Tohidplus\Paravel\Facades\Serializer;
use Tohidplus\Paravel\Response\ResponseList;

class ParallelProcessor
{
    /**
     * @var Process[] $processes
     */
    protected array $processes = [];

    /**
     * @var array
     */
    private array $config;

    /**
     * @var ResponseList $responses
     */
    private ResponseList $responses;

    /**
     * ParallelProcessor constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->clear();
        $this->config = $config;
    }

    /**
     * @param string $label
     * @param Closure $closure
     * @return ParallelProcessor
     */
    public function add(string $label, Closure $closure)
    {
        $this->processes[] = new Process($this->config['artisan_path'], new ProcessHandler($label, $closure));
        return $this;
    }

    /**
     * @return void
     */
    public function run()
    {
        $this->runProcesses();
        $this->clear();
    }

    /**
     * @return ResponseList
     */
    public function wait()
    {
        $this->runProcesses();
        $this->fetchResults();
        $responses = $this->responses;
        $this->clear();
        return $responses;
    }

    /**
     * @return void
     */
    private function clear()
    {
        $this->processes = [];
        $this->responses = new ResponseList([]);
    }

    /**
     * @return void
     */
    private function runProcesses(): void
    {
        foreach ($this->processes as $process) {
            $process->start();
        }
    }

    /**
     * @return void
     */
    private function fetchResults(): void
    {
        while (count($this->processes)) {
            foreach ($this->processes as $index => $process) {
                if (!$process->isRunning()) {
                    unset($this->processes[$index]);
                    $this->responses->add($process);
                }
            }
        }
    }
}
