<?php


namespace Tohidplus\Paravel\Serializer;

use Closure;
use SuperClosure\Serializer as ClosureSerializer;

class ParavelSerializer
{
    /**
     * @var ClosureSerializer
     */
    private ClosureSerializer $closureSerializer;

    /**
     * ParavelSerializer constructor.
     * @param ClosureSerializer $closureSerializer
     */
    public function __construct(ClosureSerializer $closureSerializer)
    {
        $this->closureSerializer = $closureSerializer;
    }

    /**
     * @param $data
     * @return string
     */
    public function base64_serialize($data)
    {
        return base64_encode(serialize($data));
    }

    /**
     * @param $data
     * @return mixed
     */
    public function base64_unserialize($data)
    {
        return unserialize(base64_decode($data));
    }

    /**
     * @param Closure $closure
     * @return string
     */
    public function closure_serialize(Closure $closure)
    {
        return $this->closureSerializer->serialize($closure);
    }

    /**
     * @param string $serialized
     * @return Closure
     */
    public function closure_unserialize(string $serialized)
    {
        return $this->closureSerializer->unserialize($serialized);
    }
}
