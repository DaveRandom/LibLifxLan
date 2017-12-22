<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Responses;

use DaveRandom\LifxLan\Messages\Message;

final class StateService extends Message
{
    public const MESSAGE_TYPE_ID = 3;

    private $service;
    private $port;

    public function __construct(int $service, int $port)
    {
        $this->service = $service;
        $this->port = $port;
    }

    public function getService(): int
    {
        return $this->service;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}
