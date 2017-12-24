<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Responses;

use DaveRandom\LifxLan\DataTypes\HostInfo;
use DaveRandom\LifxLan\Messages\Message;

final class StateHostInfo extends Message
{
    public const MESSAGE_TYPE_ID = 12;

    private $hostInfo;

    public function __construct(HostInfo $hostInfo)
    {
        $this->hostInfo = $hostInfo;
    }

    public function getHostInfo(): HostInfo
    {
        return $this->hostInfo;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}
