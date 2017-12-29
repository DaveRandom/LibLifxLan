<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Light\Responses;

use DaveRandom\LibLifxLan\DataTypes\Light\State as LightState;
use DaveRandom\LibLifxLan\Messages\Message;

final class State implements Message
{
    public const MESSAGE_TYPE_ID = 107;
    public const WIRE_SIZE = 52;

    private $state;

    public function __construct(LightState $state)
    {
        $this->state = $state;
    }

    public function getState(): LightState
    {
        return $this->state;
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
