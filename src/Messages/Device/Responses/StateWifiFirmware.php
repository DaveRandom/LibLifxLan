<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\WifiFirmware;
use DaveRandom\LibLifxLan\Messages\Message;

final class StateWifiFirmware implements Message
{
    public const MESSAGE_TYPE_ID = 19;
    public const WIRE_SIZE = 20;

    private $wifiFirmware;

    public function __construct(WifiFirmware $wifiFirmware)
    {
        $this->wifiFirmware = $wifiFirmware;
    }

    public function getWifiFirmware(): WifiFirmware
    {
        return $this->wifiFirmware;
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
