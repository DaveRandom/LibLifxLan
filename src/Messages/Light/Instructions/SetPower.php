<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Light\Instructions;

use DaveRandom\LifxLan\DataTypes\PowerTransition;
use DaveRandom\LifxLan\Messages\InstructionMessage;

final class SetPower extends InstructionMessage
{
    public const MESSAGE_TYPE_ID = 117;

    private $powerTransition;

    public function __construct(PowerTransition $powerTransition)
    {
        parent::__construct();

        $this->powerTransition = $powerTransition;
    }

    public function getPowerTransition(): PowerTransition
    {
        return $this->powerTransition;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}
