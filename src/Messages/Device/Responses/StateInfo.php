<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\TimeInfo;
use DaveRandom\LibLifxLan\Messages\Message;

final class StateInfo implements Message
{
    public const MESSAGE_TYPE_ID = 35;
    public const WIRE_SIZE = 24;

    private $info;

    public function __construct(TimeInfo $info)
    {
        $this->info = $info;
    }

    public function getInfo(): TimeInfo
    {
        return $this->info;
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
