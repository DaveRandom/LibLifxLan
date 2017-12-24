<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Instructions;

use DaveRandom\LifxLan\DataTypes\Group;
use DaveRandom\LifxLan\Messages\InstructionMessage;

final class SetGroup extends InstructionMessage
{
    public const MESSAGE_TYPE_ID = 52;

    private $group;

    public function __construct(Group $group)
    {
        parent::__construct();

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
}
