<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Commands;

use DaveRandom\LibLifxLan\DataTypes\Location;
use DaveRandom\LibLifxLan\Messages\Message;

final class SetLocation implements Message
{
    public const MESSAGE_TYPE_ID = 49;
    public const WIRE_SIZE = 56;

    private $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    public function getLocation(): Location
    {
        return $this->location;
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
