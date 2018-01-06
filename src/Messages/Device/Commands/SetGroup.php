<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Commands;

use DaveRandom\LibLifxLan\DataTypes\Group;
use DaveRandom\LibLifxLan\Messages\Message;

final class SetGroup implements Message
{
    public const MESSAGE_TYPE_ID = 52;
    public const WIRE_SIZE = 56;

    private $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    public function getGroup(): Group
    {
        return $this->group;
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
