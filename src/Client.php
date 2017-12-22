<?php declare(strict_types=1);

namespace DaveRandom\LifxLan;

abstract class Client
{
    private $socket;

    /**
     * @param resource $socket
     */
    public function __construct($socket)
    {
        $this->socket = $socket;
    }
}
