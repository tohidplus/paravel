<?php


namespace Tohidplus\Paravel\Response;


use Illuminate\Support\Collection;
use Tohidplus\Paravel\Process\Process;

class ResponseList
{
    protected Collection $responses;

    /**
     * ResponseList constructor.
     * @param array $responses
     */
    public function __construct(array $responses)
    {
        $this->responses = collect($responses);
    }

    /**
     * @param string $label
     * @return array
     */
    public function get(string $label)
    {
        return $this->responses->where('label', $label)->first();
    }

    /**
     * @param string $label
     * @return mixed|null
     */
    public function resultOf(string $label)
    {
        return $this->get($label)['result'] ?? null;
    }

    /**
     * @param string $label
     * @return mixed|null
     */
    public function errorOf(string $label)
    {
        return $this->get($label)['error'] ?? null;
    }

    /**
     * @param string $label
     * @return mixed|null
     */
    public function statusOf(string $label)
    {
        return $this->get($label)['status'] ?? null;
    }

    /**
     * @param Process $process
     */
    public function add(Process $process)
    {
        $response = new Response(...$this->getResponseArguments($process));
        $this->responses->add($response->toArray());
    }

    /**
     * @return bool
     */
    public function succeeded(): bool
    {
        return !$this->responses->where('status', '=', false)->count();
    }

    /**
     * @return bool
     */
    public function failed(): bool
    {
        return !$this->succeeded();
    }


    /**
     * @param Process $process
     * @return array
     */
    private function getResponseArguments(Process $process): array
    {
        if ($process->isSuccessful()) {
            return [$process->label(), true, $process->getOutput()];
        }
        return [$process->label(), false, null, ['output' => $process->getOutput()]];
    }
}
