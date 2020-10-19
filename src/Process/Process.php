<?php


namespace Tohidplus\Paravel\Process;

use Symfony\Component\Process\Process as SymphonyProcess;
use Tohidplus\Paravel\Facades\Serializer;

/**
 *
 * Class Process
 * @package Tohidplus\Paravel
 * @mixin SymphonyProcess
 */
class Process
{
    /**
     * @var SymphonyProcess
     */
    private SymphonyProcess $process;
    /**
     * @var ProcessHandler
     */
    private ProcessHandler $handler;

    /**
     * Process constructor.
     * @param string $path
     * @param ProcessHandler $handler
     */
    public function __construct(string $path, ProcessHandler $handler)
    {
        $this->process = new SymphonyProcess(["php", $path, "parallel:run", Serializer::base64_serialize($handler)]);
        $this->handler = $handler;
    }

    public function label()
    {
        return $this->handler->getLabel();
    }

    public function __call($name, $arguments)
    {
        return call_user_func(array($this->process, $name), ...$arguments);
    }

    public function __get($name)
    {
        return $this->process->$name;
    }

    /**
     * @return mixed|string
     */
    public function getOutput()
    {
        if ($this->isSuccessful()) {
            return Serializer::base64_unserialize($this->process->getOutput());
        }
        return $this->process->getOutput();
    }
}
