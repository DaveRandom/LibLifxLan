<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Light\Commands;

use DaveRandom\LibLifxLan\DataTypes\Light\ColorTransition;
use DaveRandom\LibLifxLan\Messages\Message;

final class SetColor implements Message
{
    public const MESSAGE_TYPE_ID = 102;
    public const WIRE_SIZE = 13;

    private $colorTransition;

    public function __construct(ColorTransition $colorTransition)
    {
        $this->colorTransition = $colorTransition;
    }

    public function getColorTransition(): ColorTransition
    {
        return $this->colorTransition;
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
