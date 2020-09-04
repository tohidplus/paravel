<?php


namespace Tohidplus\Paravel;

use Tohidplus\Paravel\Exceptions\ExecutorTimeoutException;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process as SymphonyProcess;

class Processor
{
    /**
     * @var string[] $processes
     */
    protected array $processes = [];
    /**
     * @var Connection
     */
    private Connection $redis;
    private array $config;
    /**
     * @var mixed|null
     */
    private $timeout;
    private string $listen;
    private string $reply;

    /**
     * Processor constructor.
     * @param Connection $redis
     * @param array $config
     */
    public function __construct(Connection $redis, array $config)
    {
        $this->listen = $this->randomQueue();
        $this->reply = $this->randomQueue();
        $this->redis = $redis;
        $this->config = $config;
        $this->timeout = $config['waiting_timeout'];
    }

    /**
     * @param int $timeout
     * @return $this
     */
    public function timeout(int $timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @param string $label
     * @param callable $callable
     * @return Processor
     */
    public function add(string $label, callable $callable)
    {
        $this->processes[] = new Process($label, $callable);
        return $this;
    }

    /**
     * @return Collection
     * @throws ExecutorTimeoutException
     */
    public function run(): Collection
    {
        $this->runProcesses();
        $results = $this->fetchResults();
        $this->clear();
        return $results;
    }

    /**
     * @return void
     */
    private function clear()
    {
        $this->processes = [];
    }

    /**
     * @return string
     */
    private function randomQueue()
    {
        return (string)Str::uuid();
    }

    /**
     * @return void
     */
    private function runProcesses(): void
    {
        foreach ($this->processes as $process) {
            $sProcess = new SymphonyProcess(["php", $this->config["artisan_path"], "parallel:run", $this->listen, $this->reply]);
            $sProcess->start();
            $this->redis->rpush($this->listen, serialize($process));
        }
    }

    /**
     * @return Collection
     * @throws ExecutorTimeoutException
     */
    private function fetchResults(): Collection
    {
        $results = collect([]);
        while ($results->count() < count($this->processes)) {
            $response = $this->redis->blpop($this->reply, $this->timeout);
            if (is_null($response)) {
                throw new ExecutorTimeoutException();
            }
            [$responseQueue, $response] = $response;
            $results->add(unserialize($response)->toArray());
        }
        return $results;
    }
}
