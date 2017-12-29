<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Light\Commands;

use DaveRandom\LibLifxLan\DataTypes\Light\Effect;
use DaveRandom\LibLifxLan\Messages\CommandMessage;

final class SetWaveformOptional extends CommandMessage
{
    public const MESSAGE_TYPE_ID = 119;
    public const WIRE_SIZE = 25;

    private $effect;

    public function __construct(Effect $effect)
    {
        parent::__construct();

        $this->effect = $effect;
    }

    public function getEffect(): Effect
    {
        return $this->effect;
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
