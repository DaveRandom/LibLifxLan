<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Light\Commands;

use DaveRandom\LibLifxLan\DataTypes\Light\PowerTransition;
use DaveRandom\LibLifxLan\Messages\Message;

final class SetPower implements Message
{
    public const MESSAGE_TYPE_ID = 117;
    public const WIRE_SIZE = 6;

    private $powerTransition;

    public function __construct(PowerTransition $powerTransition)
    {
        $this->powerTransition = $powerTransition;
    }

    public function getPowerTransition(): PowerTransition
    {
        return $this->powerTransition;
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
