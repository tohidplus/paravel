<?php


namespace Tohidplus\Paravel\Response;

use JsonSerializable;

class Response implements JsonSerializable
{
    protected bool $status = true;
    protected array $error = [];
    protected $result;
    private string $label;

    /**
     * Response constructor.
     * @param string $label
     * @param bool $status
     * @param mixed $result
     * @param array $error
     */
    public function __construct(string $label, bool $status, $result, array $error = [])
    {
        $this->status = $status;
        $this->result = $result;
        $this->error = $error;
        $this->label = $label;
    }

    public function jsonSerialize()
    {
        return [
            'label'=>$this->label,
            'status' => $this->status,
            'result' => $this->result,
            'error' => $this->error
        ];
    }

    public function toArray()
    {
        return $this->jsonSerialize();
    }
}
