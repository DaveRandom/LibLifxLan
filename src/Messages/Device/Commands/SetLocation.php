<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Commands;

use DaveRandom\LibLifxLan\DataTypes\Location;
use DaveRandom\LibLifxLan\Messages\CommandMessage;

final class SetLocation extends CommandMessage
{
    public const MESSAGE_TYPE_ID = 49;
    public const PAYLOAD_SIZE = 28;

    private $location;

    public function __construct(Location $location)
    {
        parent::__construct();

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
}
