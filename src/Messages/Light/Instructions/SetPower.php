<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Light\Instructions;

use DaveRandom\LifxLan\Messages\InstructionMessage;

final class SetPower extends InstructionMessage
{
    public const MESSAGE_TYPE_ID = 117;

    private $level;
    private $transitionDuration;

    public function __construct(int $level, int $transitionDuration)
    {
        $this->level = $level;
        $this->transitionDuration = $transitionDuration;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getTransitionDuration(): int
    {
        return $this->transitionDuration;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}
