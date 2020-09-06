<?php


namespace Tohidplus\Paravel;


class Serializer
{
    public function serialize($data)
    {
        return base64_encode(serialize($data));
    }

    public function unserialize($data)
    {
        return unserialize(base64_decode($data));
    }
}
