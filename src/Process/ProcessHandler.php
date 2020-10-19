<?php

namespace Tohidplus\Paravel\Process;

use Closure;
use Tohidplus\Paravel\Facades\Serializer;

class ProcessHandler
{
    /**
     * @var Closure $callable
     */
    private Closure $callable;
    /**
     * @var string $label
     */
    private string $label;

    /**
     * Process constructor.
     * @param string $label
     * @param callable $callable
     */
    public function __construct(string $label, callable $callable)
    {
        $this->callable = $callable;
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        return call_user_func($this->callable);
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return array
     */
    public function __serialize()
    {
        return [
            'label' => $this->label,
            'callable' => Serializer::closure_serialize($this->callable)
        ];
    }

    /**
     * @param array $data
     */
    public function __unserialize(array $data)
    {
        $this->label = $data['label'];
        $this->callable = Serializer::closure_unserialize($data['callable']);
    }
}
