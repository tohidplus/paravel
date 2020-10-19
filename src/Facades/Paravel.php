<?php


namespace Tohidplus\Paravel\Facades;


use Closure;
use Illuminate\Support\Facades\Facade;
use Tohidplus\Paravel\Process\ParallelProcessor;
use Tohidplus\Paravel\Response\ResponseList;

/**
 *
 * Class Paravel
 * @package Tohidplus\Paravel\Facades
 * @method static ParallelProcessor add(string $label, Closure $closure)
 * @method static void run()
 * @method static ResponseList wait()
 */
class Paravel extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'paravel';
    }
}
