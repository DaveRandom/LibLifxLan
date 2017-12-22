<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Encoding;

abstract class Encoder
{
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @param int $option
     * @return mixed
     */
    public function getOption(int $option)
    {
        return $this->options[$option] ?? null;
    }

    /**
     * @param int $option
     * @param mixed $value
     * @return $this
     */
    public function setOption(int $option, $value): self
    {
        $this->options[$option] = $value;

        return $this;
    }
}
