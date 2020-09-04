<?php


namespace Tohidplus\Paravel;


class Paravel
{
    private function __construct()
    {
        //
    }

    /**
     * @return Processor
     */
    public static function create(): Processor
    {
        return app(Processor::class);
    }
}
