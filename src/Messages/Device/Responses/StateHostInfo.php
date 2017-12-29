<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\HostInfo;
use DaveRandom\LibLifxLan\Messages\Message;

final class StateHostInfo implements Message
{
    public const MESSAGE_TYPE_ID = 12;
    public const WIRE_SIZE = 14;

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

    public function getWireSize(): int
    {
        return self::WIRE_SIZE;
    }
}
