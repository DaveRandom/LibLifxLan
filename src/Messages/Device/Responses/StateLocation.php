<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Responses;

use DaveRandom\LifxLan\DataTypes\Location;
use DaveRandom\LifxLan\Messages\ResponseMessage;

final class StateLocation extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 50;
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
