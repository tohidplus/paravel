<?php


namespace Tohidplus\Paravel\Console\Commands;

use Tohidplus\Paravel\Process;
use Tohidplus\Paravel\Response;
use Illuminate\Console\Command;
use Illuminate\Redis\Connections\Connection;

class Executor extends Command
{
    protected $signature = "parallel:run {listen} {reply}";
    protected $description = "Runs parallel processes";
    protected Connection $redis;

    public function __construct(Connection $redis)
    {
        parent::__construct();
        $this->redis = $redis;
    }

    public function handle()
    {
        [$queue, $message] = $this->redis->blpop($this->argument('listen'), 0);
        $process = unserialize($message);
        try {
            /** @var Process $message */
            $result = $process->handle();
            $response = new Response($process->getLabel(), true, $result);
        } catch (\Throwable $exception) {
            $response = new Response($process->getLabel(), false, null, [
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage()
            ]);
        }
        $this->redis->rpush($this->argument('reply'), serialize($response));
    }
}
