<?php


namespace Tohidplus\Paravel;

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
     * @var SymphonyProcess[]
     */
    protected array $sProcesses = [];

    /**
     * @var array
     */
    private array $config;

    /**
     * @var Serializer
     */
    private Serializer $serializer;

    /**
     * Processor constructor.
     * @param array $config
     * @param Serializer $serializer
     */
    public function __construct(array $config, Serializer $serializer)
    {
        $this->config = $config;
        $this->serializer = $serializer;
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
     * @return void
     */
    private function runProcesses(): void
    {
        foreach ($this->processes as $process) {
            $sProcess = new SymphonyProcess(["php", $this->config["artisan_path"], "parallel:run", $this->serializer->serialize($process)]);
            $this->sProcesses[] = $sProcess;
            $sProcess->start();
        }
    }

    /**
     * @return ResponseList
     */
    private function fetchResults(): ResponseList
    {
        $responses = new ResponseList([]);
        while (count($this->sProcesses)) {
            foreach ($this->sProcesses as $index => $sProcess) {
                if (!$sProcess->isRunning()) {
                    unset($this->sProcesses[$index]);
                    $label = $this->processes[$index]->getLabel();
                    if ($sProcess->isSuccessful()) {
                        $responses->add(new Response($label, true, $this->serializer->unserialize($sProcess->getOutput())
                        ));
                    } else {
                        $responses->add(new Response($label, false, null, ['output' => $sProcess->getOutput()]));
                    }
                }
            }
        }
        return $responses;
    }
}
