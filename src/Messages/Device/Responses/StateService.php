<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Responses;

use DaveRandom\LifxLan\DataTypes\Service;
use DaveRandom\LifxLan\Messages\Message;

final class StateService extends Message
{
    public const MESSAGE_TYPE_ID = 3;

    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function getService(): Service
    {
        return $this->service;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}
