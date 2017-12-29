<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Commands;

use DaveRandom\LibLifxLan\Messages\Message;

final class SetPower implements Message
{
    public const MESSAGE_TYPE_ID = 21;
    public const WIRE_SIZE = 2;

    private $level;

    public function __construct(int $level)
    {
        $this->level = $level;
    }

    public function getLevel(): int
    {
        return $this->level;
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
