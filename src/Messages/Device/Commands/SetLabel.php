<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Commands;

use DaveRandom\LibLifxLan\DataTypes\Label;
use DaveRandom\LibLifxLan\Messages\Message;

final class SetLabel implements Message
{
    public const MESSAGE_TYPE_ID = 24;
    public const WIRE_SIZE = 32;

    private $label;

    public function __construct(Label $label)
    {
        $this->label = $label;
    }

    public function getLabel(): Label
    {
        return $this->label;
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
