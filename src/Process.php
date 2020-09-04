<?php


namespace Tohidplus\Paravel;

use SuperClosure\Serializer;

class Process
{
    /**
     * @var callable
     */
    private $callable;
    private string $label;
    /**
     * @var Serializer
     */
    private Serializer $serializer;

    /**
     * Process constructor.
     * @param string $label
     * @param callable $callable
     */
    public function __construct(string $label, callable $callable)
    {
        $this->serializer = new Serializer();
        $this->callable = $this->serializer->serialize($callable);
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        $func = $this->serializer->unserialize($this->callable);
        return $func();
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }
}
