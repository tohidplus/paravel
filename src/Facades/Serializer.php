<?php


namespace Tohidplus\Paravel\Facades;


use Closure;
use Illuminate\Support\Facades\Facade;

/**
 *
 * Class Serializer
 * @package Tohidplus\Paravel\Facades
 * @method static string base64_serialize($data)
 * @method static mixed base64_unserialize($data)
 * @method static string closure_serialize(Closure $closure)
 * @method static Closure closure_unserialize(string $serialized)
 */
class Serializer extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'paravel-serializer';
    }
}
