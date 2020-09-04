<?php


namespace Tohidplus\Paravel\Exceptions;


use Exception;

class ExecutorTimeoutException extends Exception
{
    public function __construct($message = "Executor timeout!", $code = 500)
    {
        parent::__construct($message, $code);
    }
}
