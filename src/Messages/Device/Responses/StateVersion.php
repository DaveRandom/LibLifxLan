<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\Version;
use DaveRandom\LibLifxLan\Messages\Message;

final class StateVersion implements Message
{
    public const MESSAGE_TYPE_ID = 33;
    public const WIRE_SIZE = 12;

    private $version;

    public function __construct(Version $version)
    {
        $this->version = $version;
    }

    public function getVersion(): Version
    {
        return $this->version;
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
